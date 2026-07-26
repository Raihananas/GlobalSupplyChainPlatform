<?php

namespace App\Http\Controllers;

use App\Models\NewsCache;
use App\Services\GNewsService;
use App\Services\RealtimeNewsService;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(
        private GNewsService $newsService,
        private RealtimeNewsService $realtimeService,
        private SentimentAnalysisService $sentiment
    ) {}

    public function index(Request $request)
    {
        $topic  = $request->input('topic','general');
        $query  = $request->input('query', null);
        
        // Jika ada custom query (untuk berita umum seperti Piala Dunia, dll)
        if ($query) {
            $this->newsService->searchNews($query);
        } else {
            // Fetch news by topic
            $this->newsService->getNewsByTopic($topic);
        }
        
        $this->analyzeUnprocessed();

        // Get news based on topic or query
        $newsQuery = NewsCache::recent(72)->orderByDesc('published_at');
        if ($query) {
            $newsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        } elseif ($topic !== 'general') {
            $newsQuery->byTopic($topic);
        }
        
        $newsList = $newsQuery->paginate(12);
        $sentimentSummary = $this->sentimentSummary($topic, $query);
        
        // Extended topics including general news
        $topics = [
            'general' => 'General News',
            'logistics' => 'Logistics',
            'trade' => 'Trade',
            'shipping' => 'Shipping',
            'economy' => 'Economy',
            'geopolitics' => 'Geopolitics',
            'sports' => 'Sports',
            'technology' => 'Technology',
            'business' => 'Business',
            'breaking' => '🔴 Breaking News',
            'worldcup' => '⚽ World Cup',
        ];

        return view('news.index', compact('newsList','topic','topics','sentimentSummary','query'));
    }

    public function byTopic(string $topic)
    {
        $allowedTopics = ['general','logistics','trade','shipping','economy','geopolitics','sports','technology','business','breaking','worldcup'];
        if (!in_array($topic, $allowedTopics)) abort(404);
        
        // Fetch fresh news for specific topics
        if ($topic === 'breaking') {
            $this->newsService->getBreakingNews();
        } elseif ($topic === 'worldcup') {
            $this->newsService->getWorldCupNews();
        } else {
            $this->newsService->getNewsByTopic($topic);
        }
        
        $this->analyzeUnprocessed();

        $newsQuery = NewsCache::recent(72)->orderByDesc('published_at');
        if ($topic !== 'general') {
            $newsQuery->byTopic($topic);
        }
        
        $newsList = $newsQuery->paginate(12);
        $sentimentSummary = $this->sentimentSummary($topic);
        
        $topics = [
            'general' => 'General News',
            'logistics' => 'Logistics',
            'trade' => 'Trade',
            'shipping' => 'Shipping',
            'economy' => 'Economy',
            'geopolitics' => 'Geopolitics',
            'sports' => 'Sports',
            'technology' => 'Technology',
            'business' => 'Business',
            'breaking' => '🔴 Breaking News',
            'worldcup' => '⚽ World Cup',
        ];

        if (request()->ajax()) return response()->json(['news'=>$newsList->items(),'sentiment'=>$sentimentSummary]);
        return view('news.index', compact('newsList','topic','topics','sentimentSummary'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('news.index');
        }
        
        // Search news dengan custom query
        $this->newsService->searchNews($query);
        $this->analyzeUnprocessed();
        
        $newsList = NewsCache::recent(72)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderByDesc('published_at')
            ->paginate(12);
        
        $sentimentSummary = $this->sentimentSummary(null, $query);
        $topics = [
            'general' => 'General News',
            'logistics' => 'Logistics',
            'trade' => 'Trade',
            'shipping' => 'Shipping',
            'economy' => 'Economy',
            'geopolitics' => 'Geopolitics',
            'sports' => 'Sports',
            'technology' => 'Technology',
            'business' => 'Business',
            'breaking' => '🔴 Breaking News',
            'worldcup' => '⚽ World Cup',
        ];
        $topic = 'general';
        
        if (request()->ajax()) {
            return response()->json(['news'=>$newsList->items(),'sentiment'=>$sentimentSummary]);
        }
        
        return view('news.index', compact('newsList','topic','topics','sentimentSummary','query'));
    }

    /**
     * API Endpoint: Real-time world news
     */
    public function realtimeWorld()
    {
        $news = $this->realtimeService->getWorldNews(15);
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'timestamp' => now(),
            'count' => count($news),
        ]);
    }

    /**
     * API Endpoint: World Cup news real-time
     */
    public function worldCupNews()
    {
        $news = $this->realtimeService->getWorldCupNews(12);
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'timestamp' => now(),
            'count' => count($news),
            'category' => 'World Cup',
        ]);
    }

    /**
     * API Endpoint: Breaking news real-time
     */
    public function breakingNews()
    {
        $news = $this->realtimeService->getBreakingNews(10);
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'timestamp' => now(),
            'count' => count($news),
            'category' => 'Breaking News',
            'priority' => 'high',
        ]);
    }

    /**
     * API Endpoint: Topic-specific real-time news
     */
    public function topicNews(string $topic)
    {
        $allowedTopics = ['general','logistics','trade','shipping','economy','geopolitics','sports','technology','business'];
        if (!in_array($topic, $allowedTopics)) {
            return response()->json(['error' => 'Topic not found'], 404);
        }

        $news = $this->realtimeService->getTopicNews($topic, 12);
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'timestamp' => now(),
            'topic' => $topic,
            'count' => count($news),
        ]);
    }

    /**
     * API Endpoint: Search news real-time
     */
    public function searchRealtime(Request $request)
    {
        $query = $request->input('q');
        if (empty($query)) {
            return response()->json(['error' => 'Query is required'], 400);
        }

        $news = $this->realtimeService->searchNews($query, 15);
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'timestamp' => now(),
            'query' => $query,
            'count' => count($news),
        ]);
    }

    /**
     * API Endpoint: Latest news feed
     */
    public function latestFeed()
    {
        $feed = $this->realtimeService->getLatestFeed(20);
        return response()->json([
            'status' => 'success',
            'data' => $feed,
            'timestamp' => now(),
            'count' => count($feed),
        ]);
    }

    /**
     * API Endpoint: Trending news
     */
    public function trendingNews()
    {
        $news = $this->realtimeService->getTrendingNews(10);
        $stats = $this->realtimeService->getNewsStatistics();
        
        return response()->json([
            'status' => 'success',
            'data' => $news,
            'statistics' => $stats,
            'timestamp' => now(),
        ]);
    }

    /**
     * API Endpoint: News statistics
     */
    public function newsStatistics()
    {
        $stats = $this->realtimeService->getNewsStatistics();
        return response()->json([
            'status' => 'success',
            'data' => $stats,
            'timestamp' => now(),
        ]);
    }

    /**
     * Web View: World Cup News page
     */
    public function worldCupPage()
    {
        $news = $this->realtimeService->getWorldCupNews(15);
        $stats = $this->realtimeService->getNewsStatistics();
        
        return view('news.worldcup', compact('news', 'stats'));
    }

    /**
     * Web View: Breaking News page
     */
    public function breakingNewsPage()
    {
        $news = $this->realtimeService->getBreakingNews(15);
        $stats = $this->realtimeService->getNewsStatistics();
        
        return view('news.breaking', compact('news', 'stats'));
    }

    /**
     * Web View: Real-time dashboard
     */
    public function realtimeDashboard()
    {
        $byTopics = $this->realtimeService->getNewsByTopics();
        $stats = $this->realtimeService->getNewsStatistics();
        $trending = $this->realtimeService->getTrendingNews(5);
        $impactful = $this->realtimeService->getImpactfulNews(5);

        return view('news.realtime-dashboard', compact('byTopics', 'stats', 'trending', 'impactful'));
    }

    private function analyzeUnprocessed(): void
    {
        NewsCache::where('positive_count',0)->where('negative_count',0)->latest()->take(20)->get()->each(function ($news) {
            $r = $this->sentiment->analyze("{$news->title} {$news->description}");
            $news->update(['positive_count'=>$r['positive_count'],'negative_count'=>$r['negative_count'],'neutral_count'=>$r['neutral_count'],'sentiment'=>$r['sentiment'],'sentiment_score'=>$r['sentiment_score']]);
        });
    }

    private function sentimentSummary(?string $topic, ?string $query = null): array
    {
        $newsQuery = NewsCache::recent(48);
        
        if ($query) {
            $newsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        } elseif ($topic && $topic !== 'general') {
            $newsQuery->byTopic($topic);
        }
        
        $news = $newsQuery->get();
        
        if ($news->isEmpty()) return ['positive'=>0,'neutral'=>100,'negative'=>0,'total'=>0,'dominant'=>'neutral','avg_score'=>0];
        $total = $news->count();
        $pos   = $news->where('sentiment','positive')->count();
        $neg   = $news->where('sentiment','negative')->count();
        $neu   = $news->where('sentiment','neutral')->count();
        return [
            'positive'  => round(($pos/$total)*100,1),
            'negative'  => round(($neg/$total)*100,1),
            'neutral'   => round(($neu/$total)*100,1),
            'total'     => $total,
            'dominant'  => $pos>=$neg&&$pos>=$neu ? 'positive' : ($neg>=$neu ? 'negative' : 'neutral'),
            'avg_score' => round($news->avg('sentiment_score'),1),
        ];
    }
}
