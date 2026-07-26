<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiskScoresSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        
        $riskScores = [
            // Asia Pacific
            ['country_code' => 'ID', 'score_date' => $today, 'weather_score' => 25.5, 'inflation_score' => 20.0, 'currency_score' => 15.0, 'news_sentiment_score' => 35.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.15, 'risk_level' => 'low'],
            ['country_code' => 'CN', 'score_date' => $today, 'weather_score' => 30.0, 'inflation_score' => 45.0, 'currency_score' => 20.0, 'news_sentiment_score' => 55.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 41.0, 'risk_level' => 'medium'],
            ['country_code' => 'JP', 'score_date' => $today, 'weather_score' => 28.0, 'inflation_score' => 15.0, 'currency_score' => 12.0, 'news_sentiment_score' => 32.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 24.4, 'risk_level' => 'low'],
            ['country_code' => 'IN', 'score_date' => $today, 'weather_score' => 40.0, 'inflation_score' => 45.0, 'currency_score' => 25.0, 'news_sentiment_score' => 45.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 40.5, 'risk_level' => 'medium'],
            ['country_code' => 'SG', 'score_date' => $today, 'weather_score' => 15.0, 'inflation_score' => 20.0, 'currency_score' => 10.0, 'news_sentiment_score' => 20.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 17.5, 'risk_level' => 'low'],
            ['country_code' => 'MY', 'score_date' => $today, 'weather_score' => 28.0, 'inflation_score' => 20.0, 'currency_score' => 18.0, 'news_sentiment_score' => 30.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.2, 'risk_level' => 'low'],
            ['country_code' => 'TH', 'score_date' => $today, 'weather_score' => 32.0, 'inflation_score' => 20.0, 'currency_score' => 20.0, 'news_sentiment_score' => 35.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 29.6, 'risk_level' => 'low'],
            ['country_code' => 'VN', 'score_date' => $today, 'weather_score' => 35.0, 'inflation_score' => 20.0, 'currency_score' => 22.0, 'news_sentiment_score' => 38.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 31.9, 'risk_level' => 'medium'],
            ['country_code' => 'KR', 'score_date' => $today, 'weather_score' => 25.0, 'inflation_score' => 20.0, 'currency_score' => 15.0, 'news_sentiment_score' => 30.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 25.5, 'risk_level' => 'low'],
            ['country_code' => 'AU', 'score_date' => $today, 'weather_score' => 35.0, 'inflation_score' => 20.0, 'currency_score' => 18.0, 'news_sentiment_score' => 25.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.3, 'risk_level' => 'low'],
            ['country_code' => 'NZ', 'score_date' => $today, 'weather_score' => 30.0, 'inflation_score' => 20.0, 'currency_score' => 16.0, 'news_sentiment_score' => 28.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.8, 'risk_level' => 'low'],
            
            // Europe
            ['country_code' => 'DE', 'score_date' => $today, 'weather_score' => 18.0, 'inflation_score' => 20.0, 'currency_score' => 8.0, 'news_sentiment_score' => 28.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 21.4, 'risk_level' => 'low'],
            ['country_code' => 'GB', 'score_date' => $today, 'weather_score' => 20.0, 'inflation_score' => 20.0, 'currency_score' => 15.0, 'news_sentiment_score' => 35.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.5, 'risk_level' => 'low'],
            ['country_code' => 'FR', 'score_date' => $today, 'weather_score' => 22.0, 'inflation_score' => 20.0, 'currency_score' => 10.0, 'news_sentiment_score' => 32.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 24.4, 'risk_level' => 'low'],
            ['country_code' => 'IT', 'score_date' => $today, 'weather_score' => 25.0, 'inflation_score' => 20.0, 'currency_score' => 12.0, 'news_sentiment_score' => 38.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 28.3, 'risk_level' => 'low'],
            ['country_code' => 'ES', 'score_date' => $today, 'weather_score' => 28.0, 'inflation_score' => 20.0, 'currency_score' => 12.0, 'news_sentiment_score' => 36.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 27.6, 'risk_level' => 'low'],
            ['country_code' => 'NL', 'score_date' => $today, 'weather_score' => 20.0, 'inflation_score' => 20.0, 'currency_score' => 8.0, 'news_sentiment_score' => 26.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 21.2, 'risk_level' => 'low'],
            ['country_code' => 'PL', 'score_date' => $today, 'weather_score' => 25.0, 'inflation_score' => 45.0, 'currency_score' => 20.0, 'news_sentiment_score' => 40.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 34.5, 'risk_level' => 'medium'],
            
            // Americas
            ['country_code' => 'US', 'score_date' => $today, 'weather_score' => 22.0, 'inflation_score' => 20.0, 'currency_score' => 5.0, 'news_sentiment_score' => 30.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 22.6, 'risk_level' => 'low'],
            ['country_code' => 'CA', 'score_date' => $today, 'weather_score' => 30.0, 'inflation_score' => 20.0, 'currency_score' => 12.0, 'news_sentiment_score' => 28.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 25.4, 'risk_level' => 'low'],
            ['country_code' => 'MX', 'score_date' => $today, 'weather_score' => 32.0, 'inflation_score' => 45.0, 'currency_score' => 25.0, 'news_sentiment_score' => 45.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 40.1, 'risk_level' => 'medium'],
            ['country_code' => 'BR', 'score_date' => $today, 'weather_score' => 35.0, 'inflation_score' => 45.0, 'currency_score' => 28.0, 'news_sentiment_score' => 50.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 42.3, 'risk_level' => 'medium'],
            ['country_code' => 'AR', 'score_date' => $today, 'weather_score' => 30.0, 'inflation_score' => 100.0, 'currency_score' => 35.0, 'news_sentiment_score' => 60.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 56.5, 'risk_level' => 'medium'],
            
            // Middle East & Africa
            ['country_code' => 'AE', 'score_date' => $today, 'weather_score' => 40.0, 'inflation_score' => 20.0, 'currency_score' => 10.0, 'news_sentiment_score' => 25.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 26.0, 'risk_level' => 'low'],
            ['country_code' => 'SA', 'score_date' => $today, 'weather_score' => 45.0, 'inflation_score' => 20.0, 'currency_score' => 10.0, 'news_sentiment_score' => 35.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 31.5, 'risk_level' => 'medium'],
            ['country_code' => 'ZA', 'score_date' => $today, 'weather_score' => 35.0, 'inflation_score' => 45.0, 'currency_score' => 30.0, 'news_sentiment_score' => 50.0, 'weather_weight' => 30, 'inflation_weight' => 20, 'currency_weight' => 10, 'news_weight' => 40, 'total_score' => 43.5, 'risk_level' => 'medium'],
        ];

        foreach ($riskScores as $score) {
            DB::table('risk_scores')->updateOrInsert(
                ['country_code' => $score['country_code'], 'score_date' => $score['score_date']],
                $score
            );
            
            // Also add to risk_history
            DB::table('risk_history')->updateOrInsert(
                ['country_code' => $score['country_code'], 'recorded_date' => $score['score_date']],
                [
                    'total_score' => $score['total_score'],
                    'weather_score' => $score['weather_score'],
                    'inflation_score' => $score['inflation_score'],
                    'currency_score' => $score['currency_score'],
                    'news_score' => $score['news_sentiment_score'],
                    'risk_level' => $score['risk_level'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        $this->command->info('✓ Risk scores seeded for ' . count($riskScores) . ' countries');
    }
}
