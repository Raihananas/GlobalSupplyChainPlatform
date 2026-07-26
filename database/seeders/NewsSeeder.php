<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        $news = [
            // Sports - World Cup Final
            [
                'title' => 'Historic World Cup Final: Argentina defeats France in penalty shootout',
                'description' => 'In an epic World Cup final, Argentina claimed victory over France 4-2 on penalties after a thrilling 3-3 draw. Lionel Messi scored twice and finally lifted the World Cup trophy.',
                'content' => 'The 2022 FIFA World Cup final will be remembered as one of the greatest matches in football history. Argentina and France battled through 120 minutes of intense action...',
                'url' => 'https://example.com/world-cup-final-2022',
                'image_url' => 'https://via.placeholder.com/800x450/0052CC/FFFFFF?text=World+Cup+Final',
                'source_name' => 'FIFA News',
                'source_url' => 'https://fifa.com',
                'language' => 'en',
                'country_code' => null,
                'topic' => 'sports',
                'published_at' => $now->copy()->subHours(2),
                'fetched_at' => $now,
                'positive_count' => 25,
                'negative_count' => 5,
                'neutral_count' => 10,
                'sentiment' => 'positive',
                'sentiment_score' => 75.5,
            ],
            [
                'title' => 'Messi\'s Dream Finally Realized: World Cup Glory for Argentina',
                'description' => 'Lionel Messi, widely regarded as one of the greatest footballers of all time, has finally won the World Cup at his fifth attempt, cementing his legacy.',
                'content' => 'At 35 years old, Lionel Messi achieved what many thought might elude him forever - winning the FIFA World Cup...',
                'url' => 'https://example.com/messi-world-cup-glory',
                'image_url' => 'https://via.placeholder.com/800x450/4CAF50/FFFFFF?text=Messi+Glory',
                'source_name' => 'ESPN',
                'source_url' => 'https://espn.com',
                'language' => 'en',
                'country_code' => 'AR',
                'topic' => 'sports',
                'published_at' => $now->copy()->subHours(3),
                'fetched_at' => $now,
                'positive_count' => 30,
                'negative_count' => 2,
                'neutral_count' => 8,
                'sentiment' => 'positive',
                'sentiment_score' => 85.0,
            ],
            
            // Technology
            [
                'title' => 'AI Revolution: ChatGPT reaches 100 million users in record time',
                'description' => 'OpenAI\'s ChatGPT has become the fastest-growing consumer application in history, reaching 100 million active users in just two months.',
                'content' => 'The artificial intelligence chatbot ChatGPT has achieved unprecedented growth...',
                'url' => 'https://example.com/chatgpt-100m-users',
                'image_url' => 'https://via.placeholder.com/800x450/9C27B0/FFFFFF?text=AI+Revolution',
                'source_name' => 'Tech Crunch',
                'source_url' => 'https://techcrunch.com',
                'language' => 'en',
                'country_code' => 'US',
                'topic' => 'technology',
                'published_at' => $now->copy()->subHours(5),
                'fetched_at' => $now,
                'positive_count' => 20,
                'negative_count' => 8,
                'neutral_count' => 15,
                'sentiment' => 'positive',
                'sentiment_score' => 65.0,
            ],
            
            // Business
            [
                'title' => 'Tesla stock surges 15% on strong Q4 delivery numbers',
                'description' => 'Tesla shares jumped after the electric vehicle maker reported record quarterly deliveries, beating analyst expectations.',
                'content' => 'Tesla Inc. delivered more vehicles than expected in the fourth quarter...',
                'url' => 'https://example.com/tesla-stock-surge',
                'image_url' => 'https://via.placeholder.com/800x450/FF5722/FFFFFF?text=Tesla+Surge',
                'source_name' => 'Bloomberg',
                'source_url' => 'https://bloomberg.com',
                'language' => 'en',
                'country_code' => 'US',
                'topic' => 'business',
                'published_at' => $now->copy()->subHours(8),
                'fetched_at' => $now,
                'positive_count' => 18,
                'negative_count' => 5,
                'neutral_count' => 12,
                'sentiment' => 'positive',
                'sentiment_score' => 70.0,
            ],
            
            // Economy
            [
                'title' => 'Federal Reserve signals potential interest rate cuts in 2024',
                'description' => 'The U.S. Federal Reserve indicated it may begin cutting interest rates next year as inflation shows signs of cooling.',
                'content' => 'In a closely watched policy announcement, the Federal Reserve...',
                'url' => 'https://example.com/fed-rate-cuts-2024',
                'image_url' => 'https://via.placeholder.com/800x450/607D8B/FFFFFF?text=Fed+Policy',
                'source_name' => 'Reuters',
                'source_url' => 'https://reuters.com',
                'language' => 'en',
                'country_code' => 'US',
                'topic' => 'economy',
                'published_at' => $now->copy()->subHours(12),
                'fetched_at' => $now,
                'positive_count' => 15,
                'negative_count' => 10,
                'neutral_count' => 20,
                'sentiment' => 'neutral',
                'sentiment_score' => 55.0,
            ],
            
            // Logistics
            [
                'title' => 'Global supply chain disruptions ease as shipping costs normalize',
                'description' => 'Container shipping rates have fallen dramatically from pandemic highs, signaling improvement in global supply chains.',
                'content' => 'The cost of shipping a container from Asia to the United States has dropped...',
                'url' => 'https://example.com/supply-chain-improvement',
                'image_url' => 'https://via.placeholder.com/800x450/00BCD4/FFFFFF?text=Supply+Chain',
                'source_name' => 'Wall Street Journal',
                'source_url' => 'https://wsj.com',
                'language' => 'en',
                'country_code' => null,
                'topic' => 'logistics',
                'published_at' => $now->copy()->subHours(15),
                'fetched_at' => $now,
                'positive_count' => 22,
                'negative_count' => 3,
                'neutral_count' => 10,
                'sentiment' => 'positive',
                'sentiment_score' => 80.0,
            ],
            
            // Trade
            [
                'title' => 'US-China trade talks resume amid hopes for tariff reduction',
                'description' => 'High-level trade negotiations between the United States and China have resumed, with both sides expressing optimism.',
                'content' => 'Trade representatives from the world\'s two largest economies met in Geneva...',
                'url' => 'https://example.com/us-china-trade-talks',
                'image_url' => 'https://via.placeholder.com/800x450/3F51B5/FFFFFF?text=Trade+Talks',
                'source_name' => 'Financial Times',
                'source_url' => 'https://ft.com',
                'language' => 'en',
                'country_code' => null,
                'topic' => 'trade',
                'published_at' => $now->copy()->subHours(18),
                'fetched_at' => $now,
                'positive_count' => 12,
                'negative_count' => 8,
                'neutral_count' => 18,
                'sentiment' => 'neutral',
                'sentiment_score' => 52.0,
            ],
            
            // Geopolitics
            [
                'title' => 'UN calls for renewed peace talks in ongoing regional conflict',
                'description' => 'The United Nations Security Council has called for immediate ceasefire and resumption of peace negotiations.',
                'content' => 'UN Secretary-General addressed the Security Council, urging all parties...',
                'url' => 'https://example.com/un-peace-talks',
                'image_url' => 'https://via.placeholder.com/800x450/795548/FFFFFF?text=UN+Peace',
                'source_name' => 'UN News',
                'source_url' => 'https://news.un.org',
                'language' => 'en',
                'country_code' => null,
                'topic' => 'geopolitics',
                'published_at' => $now->copy()->subHours(24),
                'fetched_at' => $now,
                'positive_count' => 8,
                'negative_count' => 15,
                'neutral_count' => 20,
                'sentiment' => 'negative',
                'sentiment_score' => 35.0,
            ],
            
            // General World News
            [
                'title' => 'Historic climate agreement reached at COP28 summit',
                'description' => 'World leaders agreed to transition away from fossil fuels in a landmark climate deal at the UN climate conference.',
                'content' => 'Nearly 200 countries agreed to a historic climate pact...',
                'url' => 'https://example.com/cop28-climate-deal',
                'image_url' => 'https://via.placeholder.com/800x450/4CAF50/FFFFFF?text=Climate+Deal',
                'source_name' => 'BBC News',
                'source_url' => 'https://bbc.com',
                'language' => 'en',
                'country_code' => null,
                'topic' => 'general',
                'published_at' => $now->copy()->subHours(30),
                'fetched_at' => $now,
                'positive_count' => 25,
                'negative_count' => 5,
                'neutral_count' => 15,
                'sentiment' => 'positive',
                'sentiment_score' => 78.0,
            ],
            
            // Indonesia specific
            [
                'title' => 'Indonesia unveils ambitious infrastructure development plan',
                'description' => 'The Indonesian government announced a $50 billion infrastructure investment plan to boost economic growth.',
                'content' => 'President Joko Widodo unveiled a comprehensive infrastructure roadmap...',
                'url' => 'https://example.com/indonesia-infrastructure',
                'image_url' => 'https://via.placeholder.com/800x450/F44336/FFFFFF?text=Infrastructure',
                'source_name' => 'Jakarta Post',
                'source_url' => 'https://thejakartapost.com',
                'language' => 'en',
                'country_code' => 'ID',
                'topic' => 'economy',
                'published_at' => $now->copy()->subHours(36),
                'fetched_at' => $now,
                'positive_count' => 20,
                'negative_count' => 3,
                'neutral_count' => 12,
                'sentiment' => 'positive',
                'sentiment_score' => 82.0,
            ],
        ];

        foreach ($news as $article) {
            // Check if URL already exists
            $exists = DB::table('news_cache')->where('url', $article['url'])->exists();
            if (!$exists) {
                DB::table('news_cache')->insert(array_merge($article, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
        
        $this->command->info('✓ News seeded: ' . count($news) . ' articles');
    }
}
