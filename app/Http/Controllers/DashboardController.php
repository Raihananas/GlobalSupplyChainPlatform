<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\Watchlist;
use App\Models\SystemSetting;
use App\Services\RiskScoringEngine;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private RiskScoringEngine $riskEngine) {}

    public function index()
    {
        // Set max execution time untuk dashboard
        set_time_limit(180);
        
        $defaultCodes = SystemSetting::get('dashboard_default_countries', ['ID','CN','DE','AU','US','JP']);

        // Prioritas: gunakan data dari database jika ada hari ini
        $riskScores = [];
        foreach ($defaultCodes as $code) {
            // Coba ambil dari database dulu
            $cached = RiskScore::where('country_code', $code)
                ->whereDate('score_date', today())
                ->first();
            
            if ($cached) {
                // Gunakan data dari database
                $country = Country::where('code', $code)->first();
                $riskScores[$code] = [
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
                    'risk_label' => $this->getRiskLabel($cached->risk_level),
                    'risk_badge_class' => $this->getBadgeClass($cached->risk_level),
                    'marker_color' => $this->getMarkerColor($cached->risk_level),
                ];
            } else {
                // Jika tidak ada di database, calculate (dengan error handling)
                try {
                    $riskScores[$code] = $this->riskEngine->calculate($code);
                } catch (\Exception $e) {
                    \Log::warning("Failed to calculate risk for {$code}: " . $e->getMessage());
                    // Skip negara yang error
                    continue;
                }
            }
        }

        // Statistik ringkasan
        $stats = [
            'total_countries' => Country::active()->count(),
            'total_ports'     => Port::active()->count(),
            'high_risk_count' => RiskScore::whereDate('score_date', today())->whereIn('risk_level', ['high','critical'])->count(),
            'news_count'      => NewsCache::recent(24)->count(),
        ];

        // Watchlist user
        $watchlist = Auth::check()
            ? Watchlist::where('user_id', Auth::id())->with('country')->latest()->take(6)->get()
            : collect();

        // Berita terbaru
        $latestNews = NewsCache::recent(24)->orderByDesc('published_at')->take(5)->get();

        // Risk distribution untuk pie chart
        $riskDist = RiskScore::whereDate('score_date', today())
            ->selectRaw('risk_level, COUNT(*) as count')
            ->groupBy('risk_level')
            ->pluck('count','risk_level')
            ->toArray();

        // Semua countries for map markers
        $allRisks = RiskScore::whereDate('score_date', today())
            ->with('country')
            ->get()
            ->map(fn($r) => [
                'code'  => $r->country_code,
                'name'  => $r->country->name ?? $r->country_code,
                'lat'   => $r->country->latitude ?? 0,
                'lng'   => $r->country->longitude ?? 0,
                'score' => $r->total_score,
                'level' => $r->risk_level,
                'label' => $r->risk_label,
                'color' => $r->marker_color,
            ])
            ->toArray();

        return view('dashboard.index', compact('riskScores','stats','watchlist','latestNews','riskDist','allRisks','defaultCodes'));
    }
    
    private function getRiskLabel(string $level): string
    {
        return match($level) {
            'low' => 'Low Risk',
            'medium' => 'Medium Risk',
            'high' => 'High Risk',
            'critical' => 'Critical Risk',
            default => 'Unknown'
        };
    }
    
    private function getBadgeClass(string $level): string
    {
        return match($level) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark',
            default => 'secondary'
        };
    }
    
    private function getMarkerColor(string $level): string
    {
        return match($level) {
            'low' => '#198754',
            'medium' => '#ffc107',
            'high' => '#dc3545',
            'critical' => '#212529',
            default => '#6c757d'
        };
    }
}
