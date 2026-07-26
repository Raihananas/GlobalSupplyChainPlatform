<?php

namespace App\Services;

use App\Models\NewsCache;
use Illuminate\Support\Facades\Cache;

/**
 * Realtime News Service
 * Handle real-time news updates dengan smart caching
 */
class RealtimeNewsService
{
    public function __construct(
        private GNewsService $gNewsService,
        private SentimentAnalysisService $sentimentService
    ) {}

    /**
     * Get fresh world news dengan sentiment analysis
     */
    public function getWorldNews(int $limit = 15): array
    {
        $articles = $this->gNewsService->getFreshNews('general', $limit);
        return $this->enrichWithSentiment($articles);
    }

    /**
     * Get World Cup news specifically
     */
    public function getWorldCupNews(int $limit = 12): array
    {
        $articles = $this->gNewsService->getWorldCupNews($limit);
        return $this->enrichWithSentiment($articles);
    }

    /**
     * Get breaking news (highest priority, no cache)
     */
    public function getBreakingNews(int $limit = 10): array
    {
        $articles = $this->gNewsService->getBreakingNews($limit);
        return $this->enrichWithSentiment($articles);
    }

    /**
     * Get news by specific topic with real-time freshness
     */
    public function getTopicNews(string $topic, int $limit = 12): array
    {
        $articles = $this->gNewsService->getFreshNews($topic, $limit);
        return $this->enrichWithSentiment($articles);
    }

    /**
     * Search news with real-time results
     */
    public function searchNews(string $query, int $limit = 15): array
    {
        $articles = $this->gNewsService->searchFreshNews($query, $limit);
        return $this->enrichWithSentiment($articles);
    }

    /**
     * Get latest news feed (kombinasi dari berbagai topik)
     */
    public function getLatestFeed(int $limit = 20): array
    {
        $cacheKey = 'realtime:latest_feed:' . now()->format('Y-m-d-H:i');
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            $news = NewsCache::recent(24)
                ->orderByDesc('published_at')
                ->limit($limit)
                ->get();
            
            return $news->map(fn($item) => $this->formatArticle($item))->toArray();
        });
    }

    /**
     * Get news grouped by topic
     */
    public function getNewsByTopics(): array
    {
        $topics = ['breaking', 'worldcup', 'logistics', 'trade', 'economy', 'geopolitics'];
        $grouped = [];
        
        foreach ($topics as $topic) {
            $grouped[$topic] = $this->getTopicNews($topic, 5);
        }
        
        return $grouped;
    }

    /**
     * Get trending news (based on sentiment and frequency)
     */
    public function getTrendingNews(int $limit = 10): array
    {
        $cacheKey = 'realtime:trending:' . now()->format('Y-m-d-H:i');
        
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            $trendingSources = NewsCache::recent(24)
                ->select('source_name', \DB::raw('COUNT(*) as mention_count'))
                ->groupBy('source_name')
                ->orderByDesc('mention_count')
                ->limit($limit)
                ->pluck('source_name');
            
            $news = NewsCache::recent(24)
                ->whereIn('source_name', $trendingSources)
                ->orderByDesc('published_at')
                ->get();
            
            return $news->map(fn($item) => $this->formatArticle($item))->toArray();
        });
    }

    /**
     * Get news with highest sentiment impact
     */
    public function getImpactfulNews(int $limit = 10): array
    {
        return NewsCache::recent(24)
            ->where(function($q) {
                $q->where('sentiment_score', '>', 5)
                  ->orWhere('sentiment_score', '<', -5);
            })
            ->orderByDesc('sentiment_score')
            ->limit($limit)
            ->get()
            ->map(fn($item) => $this->formatArticle($item))
            ->toArray();
    }

    /**
     * Get news statistics untuk dashboard
     */
    public function getNewsStatistics(): array
    {
        $news = NewsCache::recent(24)->get();
        
        return [
            'total_articles' => $news->count(),
            'positive_count' => $news->where('sentiment', 'positive')->count(),
            'neutral_count' => $news->where('sentiment', 'neutral')->count(),
            'negative_count' => $news->where('sentiment', 'negative')->count(),
            'avg_sentiment_score' => round($news->avg('sentiment_score'), 2),
            'most_mentioned_source' => $news->groupBy('source_name')->map->count()->sortDesc()->first(),
            'topics' => $news->groupBy('topic')->map->count()->toArray(),
        ];
    }

    /**
     * Analyze unprocessed news
     */
    public function analyzeUnprocessedNews(): void
    {
        NewsCache::where('positive_count', 0)
            ->where('negative_count', 0)
            ->latest()
            ->take(50)
            ->get()
            ->each(function ($news) {
                try {
                    $text = "{$news->title} {$news->description}";
                    $result = $this->sentimentService->analyze($text);
                    
                    $news->update([
                        'positive_count' => $result['positive_count'] ?? 0,
                        'negative_count' => $result['negative_count'] ?? 0,
                        'neutral_count' => $result['neutral_count'] ?? 0,
                        'sentiment' => $result['sentiment'] ?? 'neutral',
                        'sentiment_score' => $result['sentiment_score'] ?? 0,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Sentiment Analysis Error: ' . $e->getMessage());
                }
            });
    }

    /**
     * Cache status dan monitoring
     */
    public function getCacheStatus(): array
    {
        return [
            'cached_articles' => NewsCache::recent(24)->count(),
            'cache_hit_rate' => Cache::get('realtime:cache_hit_rate', 0),
            'last_update' => Cache::get('realtime:last_update', null),
            'memory_usage' => memory_get_usage(true) / 1024 / 1024,
        ];
    }

    /**
     * Clear old cache entries
     */
    public function cleanupOldCache(): void
    {
        NewsCache::where('published_at', '<', now()->subDays(7))->delete();
        Cache::tags(['realtime'])->flush();
    }

    /**
     * Enrich articles dengan sentiment analysis
     */
    private function enrichWithSentiment(array $articles): array
    {
        return collect($articles)
            ->map(fn($article) => $this->formatArticle($article))
            ->toArray();
    }

    /**
     * Format article untuk response
     */
    private function formatArticle($article): array
    {
        if ($article instanceof NewsCache) {
            return [
                'title' => $article->title,
                'description' => $article->description,
                'content' => $article->content,
                'url' => $article->url,
                'image_url' => $article->image_url,
                'source_name' => $article->source_name,
                'source_url' => $article->source_url,
                'published_at' => $article->published_at,
                'sentiment' => $article->sentiment,
                'sentiment_score' => $article->sentiment_score,
                'sentiment_icon' => $article->sentiment_icon,
                'topic' => $article->topic,
                'time_ago' => $article->time_ago,
            ];
        }

        return [
            'title' => $article['title'] ?? '',
            'description' => $article['description'] ?? '',
            'content' => $article['content'] ?? '',
            'url' => $article['url'] ?? '',
            'image_url' => $article['image_url'] ?? null,
            'source_name' => $article['source_name'] ?? 'Unknown',
            'source_url' => $article['source_url'] ?? null,
            'published_at' => $article['published_at'] ?? now(),
            'sentiment' => $article['sentiment'] ?? 'neutral',
            'sentiment_score' => $article['sentiment_score'] ?? 0,
            'sentiment_icon' => $this->getSentimentIcon($article['sentiment'] ?? 'neutral'),
            'topic' => $article['topic'] ?? 'general',
            'time_ago' => 'just now',
        ];
    }

    /**
     * Get sentiment icon
     */
    private function getSentimentIcon(string $sentiment): string
    {
        return match($sentiment) {
            'positive' => '😊',
            'negative' => '😟',
            default => '😐'
        };
    }
}
