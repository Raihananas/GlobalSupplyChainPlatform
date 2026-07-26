# Real-time News Intelligence Feature Documentation

## Overview
Sistem berita real-time yang komprehensif dengan kemampuan untuk menampilkan berita dunia, Piala Dunia, berita breaking, dan analytics sentiment dalam waktu nyata.

## Fitur Utama

### 1. **Real-time World Cup News** ⚽
- **Route:** `/news/worldcup`
- **API:** `/api/v1/news/realtime/worldcup`
- Menampilkan berita Piala Dunia dengan update otomatis setiap 30 detik
- Sentiment analysis untuk setiap artikel
- Live indicator dengan pulsing animation

### 2. **Breaking News Alerts** 🔴
- **Route:** `/news/breaking`
- **API:** `/api/v1/news/realtime/breaking`
- Update lebih cepat (setiap 15 detik)
- Timeline view untuk urgency visualization
- Critical alerts dengan color coding

### 3. **Real-time Dashboard** ⚡
- **Route:** `/news/realtime/dashboard`
- Menampilkan news dari semua topik sekaligus
- Statistics overview (total, positive, neutral, negative)
- Trending news dan high-impact articles
- Auto-refresh dapat dikontrol (pause/resume)

### 4. **Enhanced News Index**
- **Route:** `/news` atau `/news/topic/{topic}`
- Tab baru untuk "Breaking News" dan "World Cup"
- Real-time update indicator
- Sentiment analysis visualization

## API Endpoints

### World News
```
GET /api/v1/news/realtime/world
GET /api/v1/news/realtime/worldcup
GET /api/v1/news/realtime/breaking
```

### By Topic
```
GET /api/v1/news/realtime/topic/{topic}
GET /api/v1/news/realtime/search?q={query}
GET /api/v1/news/realtime/feed
GET /api/v1/news/realtime/trending
GET /api/v1/news/realtime/stats
```

### Response Format
```json
{
  "status": "success",
  "data": [
    {
      "title": "Article Title",
      "description": "Brief description",
      "content": "Full content",
      "url": "https://source.com/article",
      "image_url": "https://source.com/image.jpg",
      "source_name": "Source Name",
      "published_at": "2026-07-21T10:30:00Z",
      "sentiment": "positive",
      "sentiment_score": 5.2,
      "sentiment_icon": "😊",
      "topic": "worldcup",
      "time_ago": "5 minutes ago"
    }
  ],
  "timestamp": "2026-07-21T10:35:00Z",
  "count": 15,
  "category": "World Cup"
}
```

## Services

### GNewsService
Extended dengan methods baru:
- `getFreshNews($topic, $max)` - Fetch tanpa cache
- `searchFreshNews($query, $max)` - Search real-time
- `getWorldCupNews($max)` - World Cup specific
- `getBreakingNews($max)` - Breaking news only

### RealtimeNewsService (New)
Service komprehensif untuk real-time updates:
- `getWorldNews()` - All world news
- `getWorldCupNews()` - World Cup focus
- `getBreakingNews()` - Breaking alerts
- `getTopicNews($topic)` - Specific topic
- `searchNews($query)` - Real-time search
- `getLatestFeed()` - Combined feed
- `getNewsByTopics()` - Grouped by topic
- `getTrendingNews()` - Trending articles
- `getImpactfulNews()` - High impact articles
- `getNewsStatistics()` - Stats overview
- `analyzeUnprocessedNews()` - Sentiment analysis
- `getCacheStatus()` - Cache monitoring
- `cleanupOldCache()` - Maintenance

### NewsController
New methods:
- `realtimeWorld()` - API endpoint
- `worldCupNews()` - API endpoint
- `breakingNews()` - API endpoint
- `topicNews($topic)` - API endpoint
- `searchRealtime($query)` - API endpoint
- `latestFeed()` - API endpoint
- `trendingNews()` - API endpoint
- `newsStatistics()` - API endpoint
- `worldCupPage()` - Web page
- `breakingNewsPage()` - Web page
- `realtimeDashboard()` - Web page

## Configuration

### Environment Variables (sudah ada di .env)
```
GNEWS_API_KEY=your_api_key_here
GNEWS_BASE_URL=https://gnews.io/api/v4
```

### Queries Supported
```php
'general'     => 'world news OR breaking news OR latest news'
'logistics'   => 'logistics OR supply chain OR shipping OR freight'
'trade'       => 'trade OR export OR import OR tariff'
'shipping'    => 'shipping OR port OR cargo OR maritime'
'economy'     => 'economy OR GDP OR inflation OR recession'
'geopolitics' => 'war OR conflict OR sanction OR embargo'
'sports'      => 'sports OR football OR soccer OR World Cup OR championship OR Olympics'
'technology'  => 'technology OR AI OR innovation OR startup'
'business'    => 'business OR company OR market OR stock'
'breaking'    => 'breaking news OR urgent OR emergency OR alert'
'worldcup'    => 'World Cup OR Piala Dunia OR FIFA OR football championship'
'events'      => 'major event OR happening OR news event OR latest update'
```

## Usage Examples

### 1. View World Cup News (Web)
```
Kunjungi: /news/worldcup
```

### 2. View Breaking News (Web)
```
Kunjungi: /news/breaking
```

### 3. View Real-time Dashboard (Web)
```
Kunjungi: /news/realtime/dashboard
```

### 4. Get World Cup News via API
```bash
curl http://localhost:8000/api/v1/news/realtime/worldcup
```

### 5. Search News Real-time via API
```bash
curl "http://localhost:8000/api/v1/news/realtime/search?q=World%20Cup"
```

### 6. Get Statistics via API
```bash
curl http://localhost:8000/api/v1/news/realtime/stats
```

## Frontend Features

### Auto-refresh
- World Cup: 30 detik
- Breaking News: 15 detik  
- Dashboard: 30 detik
- Dapat di-pause/resume via button

### Live Indicators
- Pulsing animation untuk latest news
- Timeline view untuk breaking news
- Color-coded sentiment badges
- Real-time timestamp updates

### Sentiment Analysis
- Positive: Green (😊)
- Neutral: Gray (😐)
- Negative: Red (😟)
- Impact scoring berdasarkan sentiment_score

## Database

News disimpan di tabel `news_cache` dengan fields:
- `title` - Judul artikel
- `description` - Deskripsi singkat
- `content` - Isi lengkap
- `url` - Link sumber
- `image_url` - URL gambar
- `source_name` - Nama media
- `source_url` - URL media
- `language` - Bahasa
- `country_code` - Kode negara
- `topic` - Topik berita
- `published_at` - Waktu publikasi
- `sentiment` - positive/neutral/negative
- `sentiment_score` - Nilai sentiment (-10 to 10)
- `positive_count` - Jumlah kata positif
- `negative_count` - Jumlah kata negatif
- `neutral_count` - Jumlah kata netral
- `fetched_at` - Waktu fetch dari API

## Performance Optimization

### Caching Strategy
- World news cache: 2 menit
- Topic cache: 2 menit
- Trending cache: 5 menit
- Search cache: 2 menit

### Database Cleanup
- Auto-delete news lebih dari 7 hari
- Dapat dipanggil: `$realtimeService->cleanupOldCache()`

### Batch Processing
- Sentiment analysis di-batch (max 50 per proses)
- Mencegah overload API

## Troubleshooting

### 1. Tidak ada berita muncul
- Pastikan `GNEWS_API_KEY` sudah diset di `.env`
- Periksa quota GNews API (free tier: 100 req/day)
- Cek logs: `storage/logs/laravel.log`

### 2. Sentiment analysis tidak jalan
- Pastikan `SentimentAnalysisService` sudah terinisialisasi
- Periksa format teks input untuk analysis

### 3. API rate limit
- Default: 100 requests per hari untuk free tier GNews
- Kurangi auto-refresh interval atau upgrade API key

### 4. Cache tidak ter-clear
- Run: `php artisan cache:clear`
- Run: `php artisan view:clear`

## Future Enhancements

1. **WebSocket untuk real-time push** (tidak perlu polling)
2. **Notification system** untuk breaking news
3. **Custom alerts** berdasarkan keywords
4. **News aggregation** dari multiple sources
5. **AI-powered summarization** untuk artikel panjang
6. **Historical analysis** dan trend tracking
7. **Export features** (PDF, CSV)
8. **User preferences** untuk news topics
9. **Mobile app support** dengan push notifications
10. **Multi-language support** untuk news content

## Files Modified/Created

### Services
- `app/Services/GNewsService.php` - Updated
- `app/Services/RealtimeNewsService.php` - NEW

### Controllers
- `app/Http/Controllers/NewsController.php` - Updated

### Views
- `resources/views/news/index.blade.php` - Updated
- `resources/views/news/worldcup.blade.php` - NEW
- `resources/views/news/breaking.blade.php` - NEW
- `resources/views/news/realtime-dashboard.blade.php` - NEW

### Routes
- `routes/web.php` - Updated
- `routes/api.php` - Updated

## Support
Untuk pertanyaan atau issue, silakan cek logs di `storage/logs/` atau hubungi development team.
