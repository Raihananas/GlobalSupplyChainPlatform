<?php

namespace App\Services;

use App\Models\NewsCache;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

/**
 * GNews API — Free tier: 100 req/day
 * https://gnews.io
 */
class GNewsService extends BaseApiService
{
    protected string $apiName = 'gnews';
    protected string $baseUrl = 'https://gnews.io/api/v4';

    private const QUERIES = [
        'general'    => 'world news OR breaking news OR latest news',
        'logistics'  => 'logistics OR supply chain OR shipping OR freight',
        'trade'      => 'trade OR export OR import OR tariff',
        'shipping'   => 'shipping OR port OR cargo OR maritime',
        'economy'    => 'economy OR GDP OR inflation OR recession',
        'geopolitics'=> 'war OR conflict OR sanction OR embargo',
        'sports'     => 'sports OR football OR soccer OR World Cup OR championship',
        'technology' => 'technology OR AI OR innovation OR startup',
        'business'   => 'business OR company OR market OR stock',
    ];

    public function __construct(private string $apiKey='')
    {
        $this->apiKey = config('services.gnews.key','');
    }

    public function getNewsByTopic(string $topic, int $max=10): array
    {
        $min = SystemSetting::get('cache_news_minutes', 120);
        return Cache::remember($this->cacheKey('topic',$topic), $min*60, function () use ($topic,$max) {
            if (empty($this->apiKey)) return $this->fromDb($topic,$max);
            $data = $this->get('/search', ['q'=>self::QUERIES[$topic]??$topic,'lang'=>'en','max'=>$max,'apikey'=>$this->apiKey,'sortby'=>'publishedAt']);
            if (!$data || !isset($data['articles'])) return $this->fromDb($topic,$max);
            foreach ($data['articles'] as $a) $this->saveToDb($this->normalize($a,$topic));
            return $data['articles'];
        });
    }

    public function getNewsByCountry(string $code, int $max=5): array
    {
        return Cache::remember($this->cacheKey('country',$code), 7200, function () use ($code,$max) {
            if (empty($this->apiKey)) return $this->fromDb(null,$max,$code);
            $data = $this->get('/search', ['q'=>'supply chain OR logistics OR trade','country'=>strtolower($code),'max'=>$max,'apikey'=>$this->apiKey,'sortby'=>'publishedAt']);
            if (!$data || !isset($data['articles'])) return $this->fromDb(null,$max,$code);
            foreach ($data['articles'] as $a) $this->saveToDb($this->normalize($a,'general',$code));
            return $data['articles'];
        });
    }

    private function normalize(array $raw, string $topic='general', string $code=null): array
    {
        return ['title'=>$raw['title']??'','description'=>$raw['description']??'','content'=>$raw['content']??'','url'=>$raw['url']??'','image_url'=>$raw['image']??null,'source_name'=>$raw['source']['name']??'Unknown','source_url'=>$raw['source']['url']??null,'language'=>'en','country_code'=>$code,'topic'=>$topic,'published_at'=>$raw['publishedAt']??now()->toDateTimeString()];
    }

    private function saveToDb(array $a): void
    {
        if (empty($a['url']) || NewsCache::where('url',$a['url'])->exists()) return;
        NewsCache::create(array_merge($a,['fetched_at'=>now(),'sentiment'=>'neutral']));
    }

    private function fromDb(?string $topic, int $max, ?string $code=null): array
    {
        $q = NewsCache::recent(72)->orderByDesc('published_at')->limit($max);
        if ($topic) $q->byTopic($topic);
        if ($code)  $q->forCountry($code);
        return $q->get()->toArray();
    }

    public function getCountrySentimentScore(string $code): float
    {
        $news = NewsCache::forCountry($code)->recent(48)->get();
        if ($news->isEmpty()) return 50.0;
        return $news->avg(fn($n)=>$n->news_risk_score) ?? 50.0;
    }
    
    public function searchNews(string $query, int $max=10): array
    {
        $min = SystemSetting::get('cache_news_minutes', 120);
        $cacheKey = $this->cacheKey('search', md5($query));
        
        return Cache::remember($cacheKey, $min*60, function () use ($query,$max) {
            if (empty($this->apiKey)) return $this->fromDbByQuery($query,$max);
            
            $data = $this->get('/search', [
                'q' => $query,
                'lang' => 'en',
                'max' => $max,
                'apikey' => $this->apiKey,
                'sortby' => 'publishedAt'
            ]);
            
            if (!$data || !isset($data['articles'])) return $this->fromDbByQuery($query,$max);
            
            foreach ($data['articles'] as $a) {
                $this->saveToDb($this->normalize($a,'general'));
            }
            
            return $data['articles'];
        });
    }
    
    private function fromDbByQuery(string $query, int $max): array
    {
        return NewsCache::recent(72)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderByDesc('published_at')
            ->limit($max)
            ->get()
            ->toArray();
    }

    /**
     * Fetch fresh news real-time tanpa cache (untuk live updates)
     */
    public function getFreshNews(string $topic='general', int $max=10): array
    {
        if (empty($this->apiKey)) return $this->fromDb($topic, $max);
        
        try {
            $data = $this->get('/search', [
                'q' => self::QUERIES[$topic] ?? $topic,
                'lang' => 'en',
                'max' => $max,
                'apikey' => $this->apiKey,
                'sortby' => 'publishedAt'
            ]);
            
            if (!$data || !isset($data['articles'])) return $this->fromDb($topic, $max);
            
            foreach ($data['articles'] as $a) {
                $this->saveToDb($this->normalize($a, $topic));
            }
            
            return $data['articles'];
        } catch (\Exception $e) {
            \Log::error('GNews API Error: ' . $e->getMessage());
            return $this->fromDb($topic, $max);
        }
    }

    /**
     * Search fresh news without cache
     */
    public function searchFreshNews(string $query, int $max=15): array
    {
        if (empty($this->apiKey)) return $this->fromDbByQuery($query, $max);
        
        try {
            $data = $this->get('/search', [
                'q' => $query,
                'lang' => 'en',
                'max' => $max,
                'apikey' => $this->apiKey,
                'sortby' => 'publishedAt'
            ]);
            
            if (!$data || !isset($data['articles'])) return $this->fromDbByQuery($query, $max);
            
            foreach ($data['articles'] as $a) {
                $this->saveToDb($this->normalize($a, 'general'));
            }
            
            return $data['articles'];
        } catch (\Exception $e) {
            \Log::error('GNews Search Error: ' . $e->getMessage());
            return $this->fromDbByQuery($query, $max);
        }
    }

    /**
     * Get World Cup news specifically
     */
    public function getWorldCupNews(int $max=12): array
    {
        return $this->getFreshNews('worldcup', $max);
    }

    /**
     * Get breaking news
     */
    public function getBreakingNews(int $max=15): array
    {
        return $this->getFreshNews('breaking', $max);
    }
}
