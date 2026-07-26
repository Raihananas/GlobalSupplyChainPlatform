<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskHistory;
use App\Models\NewsCache;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Supply Chain Risk Scoring Engine
 *
 * Algoritma: Weighted Risk Model
 * Risk Score = (Weather×30%) + (Inflation×20%) + (Currency×10%) + (NewsS×40%)
 *
 * Bobot konfigurabel via Admin → system_settings
 *
 * Output:
 *   Germany : 22 (Low Risk)
 *   China   : 47 (Medium Risk)
 */
class RiskScoringEngine
{
    public function __construct(
        private OpenMeteoService        $weather,
        private WorldBankService        $worldBank,
        private ExchangeRateService     $currency,
        private GNewsService            $news,
        private SentimentAnalysisService $sentiment
    ) {}

    public function calculate(string $code): array
    {
        $key = "risk_score:{$code}:".today()->format('Y-m-d');
        // Extend cache to 6 hours to reduce API calls
        return Cache::remember($key, 21600, function () use ($code) {
            $country = Country::where('code',$code)->first();
            if (!$country) return $this->defaultScore($code,'Country not found');

            $weights = SystemSetting::getRiskWeights();

            // ── Komponen 1: Weather Risk ──────────────────────────
            try {
                $weatherData  = $this->weather->getCurrentWeather($country->latitude??0, $country->longitude??0, $code);
                $weatherScore = (float)($weatherData['weather_risk_score'] ?? 20.0);
            } catch (\Exception $e) {
                \Log::warning("Weather API failed for {$code}: " . $e->getMessage());
                $weatherData = [];
                $weatherScore = 20.0; // Default safe score
            }

            // ── Komponen 2: Inflation Risk ────────────────────────
            try {
                $econData = $this->worldBank->getEconomicIndicators($code);
                $inflationScore = $this->inflationScore($econData);
            } catch (\Exception $e) {
                \Log::warning("World Bank API failed for {$code}: " . $e->getMessage());
                $econData = [];
                $inflationScore = 30.0; // Default moderate score
            }

            // ── Komponen 3: Currency Risk ─────────────────────────
            try {
                $currencyScore = $country->currency_code && $country->currency_code!=='USD'
                    ? $this->currency->getCurrencyRiskScore($country->currency_code)
                    : 0.0;
            } catch (\Exception $e) {
                \Log::warning("Currency API failed for {$code}: " . $e->getMessage());
                $currencyScore = 10.0; // Default low score
            }

            // ── Komponen 4: News Sentiment Risk ───────────────────
            try {
                $newsScore = $this->newsScore($code, $country->name);
            } catch (\Exception $e) {
                \Log::warning("News sentiment failed for {$code}: " . $e->getMessage());
                $newsScore = 40.0; // Default moderate score
            }

            // ── Total Weighted Score ──────────────────────────────
            $total = round(min(100, max(0,
                ($weatherScore   * $weights['weather']   / 100) +
                ($inflationScore * $weights['inflation']  / 100) +
                ($currencyScore  * $weights['currency']   / 100) +
                ($newsScore      * $weights['news']       / 100)
            )), 2);

            $level = $this->level($total);

            $result = [
                'country_code'         => $code,
                'country_name'         => $country->name,
                'flag_emoji'           => $country->flag_emoji,
                'score_date'           => today()->toDateString(),
                'weather_score'        => round($weatherScore,2),
                'inflation_score'      => round($inflationScore,2),
                'currency_score'       => round($currencyScore,2),
                'news_sentiment_score' => round($newsScore,2),
                'weather_weight'       => $weights['weather'],
                'inflation_weight'     => $weights['inflation'],
                'currency_weight'      => $weights['currency'],
                'news_weight'          => $weights['news'],
                'total_score'          => $total,
                'risk_level'           => $level,
                'risk_label'           => $this->label($level),
                'risk_badge_class'     => $this->badge($level),
                'marker_color'         => $this->color($level),
                'raw_weather'          => $weatherData,
                'raw_economic'         => $econData,
            ];

            $this->persist($result);
            return $result;
        });
    }

    public function calculateMultiple(array $codes): array
    {
        $out = [];
        foreach ($codes as $c) $out[$c] = $this->calculate($c);
        uasort($out, fn($a,$b) => $b['total_score'] <=> $a['total_score']);
        return $out;
    }

    public function compare(string $codeA, string $codeB): array
    {
        // Prioritas: gunakan data dari database jika ada
        $a = $this->calculateOrGetCached($codeA);
        $b = $this->calculateOrGetCached($codeB);
        
        $winner = $a['total_score'] <= $b['total_score'] ? $codeA : $codeB;
        $winnerName = $winner===$codeA ? $a['country_name'] : $b['country_name'];
        $loserName  = $winner===$codeA ? $b['country_name'] : $a['country_name'];
        $wScore     = $winner===$codeA ? $a['total_score'] : $b['total_score'];
        $lScore     = $winner===$codeA ? $b['total_score'] : $a['total_score'];
        $reason     = "{$winnerName} direkomendasikan untuk supply chain dengan risk score ".number_format($wScore,1)." vs {$loserName}: ".number_format($lScore,1).". Selisih ".round(abs($wScore-$lScore),1)." poin menunjukkan kondisi logistik, cuaca, sentimen berita, dan ekonomi yang lebih stabil.";
        return ['country_a'=>$a,'country_b'=>$b,'winner_risk'=>$winner,'winner_inflation'=>$a['inflation_score']<=$b['inflation_score']?$codeA:$codeB,'recommendation'=>$winner,'recommendation_reason'=>$reason];
    }

    private function calculateOrGetCached(string $code): array
    {
        // Coba ambil dari database dulu
        $cached = RiskScore::where('country_code', $code)
            ->whereDate('score_date', today())
            ->first();
        
        if ($cached) {
            $country = Country::where('code', $code)->first();
            return [
                'country_code' => $cached->country_code,
                'country_name' => $country->name ?? $code,
                'flag_emoji' => $country->flag_emoji ?? '🏳️',
                'score_date' => $cached->score_date,
                'weather_score' => $cached->weather_score,
                'inflation_score' => $cached->inflation_score,
                'currency_score' => $cached->currency_score,
                'news_sentiment_score' => $cached->news_sentiment_score,
                'weather_weight' => $cached->weather_weight,
                'inflation_weight' => $cached->inflation_weight,
                'currency_weight' => $cached->currency_weight,
                'news_weight' => $cached->news_weight,
                'total_score' => $cached->total_score,
                'risk_level' => $cached->risk_level,
                'risk_label' => $this->label($cached->risk_level),
                'risk_badge_class' => $this->badge($cached->risk_level),
                'marker_color' => $this->color($cached->risk_level),
            ];
        }
        
        // Fallback ke calculation
        return $this->calculate($code);
    }

    // ── Helpers ───────────────────────────────────────────────

    private function inflationScore(?array $data): float
    {
        if (!$data || !isset($data['inflation'])) return 30.0;
        $v = abs((float)$data['inflation']);
        if ($v<=2) return 5.0; if ($v<=5) return 20.0; if ($v<=10) return 45.0;
        if ($v<=15) return 65.0; if ($v<=20) return 80.0; return 100.0;
    }

    private function newsScore(string $code, string $name): float
    {
        // Use cached news data only, skip live API calls for dashboard
        $recentNews = NewsCache::recent(48)->get();
        
        if ($recentNews->isEmpty()) {
            return 40.0; // Default moderate score if no news
        }

        // Calculate average from cached news only (no real-time processing)
        $scores = $recentNews->filter(function($n) {
            return $n->news_risk_score !== null && $n->news_risk_score > 0;
        })->pluck('news_risk_score');

        return $scores->isNotEmpty() ? round($scores->avg(), 2) : 40.0;
    }

    private function level(float $score): string
    {
        $t = SystemSetting::getRiskThresholds();
        if ($score<=$t['low']) return 'low';
        if ($score<=$t['medium']) return 'medium';
        if ($score<=$t['high']) return 'high';
        return 'critical';
    }

    private function label(string $l): string  { return match($l){'low'=>'Low Risk','medium'=>'Medium Risk','high'=>'High Risk','critical'=>'Critical Risk',default=>'Unknown'}; }
    private function badge(string $l): string  { return match($l){'low'=>'success','medium'=>'warning','high'=>'danger','critical'=>'dark',default=>'secondary'}; }
    private function color(string $l): string  { return match($l){'low'=>'#198754','medium'=>'#ffc107','high'=>'#dc3545','critical'=>'#212529',default=>'#6c757d'}; }

    private function defaultScore(string $code, string $reason=''): array
    {
        return ['country_code'=>$code,'country_name'=>$code,'flag_emoji'=>'🏳️','score_date'=>today()->toDateString(),'weather_score'=>0.0,'inflation_score'=>0.0,'currency_score'=>0.0,'news_sentiment_score'=>0.0,'total_score'=>0.0,'risk_level'=>'low','risk_label'=>'Low Risk','risk_badge_class'=>'success','marker_color'=>'#198754','error'=>$reason];
    }

    private function persist(array $r): void
    {
        RiskScore::updateOrCreate(
            ['country_code'=>$r['country_code'],'score_date'=>$r['score_date']],
            ['weather_score'=>$r['weather_score'],'inflation_score'=>$r['inflation_score'],'currency_score'=>$r['currency_score'],'news_sentiment_score'=>$r['news_sentiment_score'],'weather_weight'=>$r['weather_weight'],'inflation_weight'=>$r['inflation_weight'],'currency_weight'=>$r['currency_weight'],'news_weight'=>$r['news_weight'],'total_score'=>$r['total_score'],'risk_level'=>$r['risk_level'],'raw_data'=>json_encode(['weather'=>$r['raw_weather'],'economic'=>$r['raw_economic']])]
        );
        RiskHistory::firstOrCreate(
            ['country_code'=>$r['country_code'],'recorded_date'=>$r['score_date']],
            ['total_score'=>$r['total_score'],'weather_score'=>$r['weather_score'],'inflation_score'=>$r['inflation_score'],'currency_score'=>$r['currency_score'],'news_score'=>$r['news_sentiment_score'],'risk_level'=>$r['risk_level']]
        );
    }
}
