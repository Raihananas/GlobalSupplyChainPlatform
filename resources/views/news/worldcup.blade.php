@extends('layouts.app')
@section('title','World Cup News - Live Updates')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('news.index') }}">News Intelligence</a></li>
<li class="breadcrumb-item active">⚽ World Cup Live</li>
@endsection
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-0 fw-bold"><i class="bi bi-trophy text-warning me-2"></i>World Cup Live Coverage</h3>
    <small class="text-muted">Real-time updates from around the world</small>
  </div>
  <button class="btn btn-primary" id="refreshBtn" onclick="refreshNews()">
    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Now
  </button>
</div>

<!-- Live indicator -->
<div class="alert alert-success alert-dismissible fade show mb-4" id="liveIndicator">
  <i class="bi bi-circle-fill text-success me-2" style="animation: blink 1s infinite;"></i>
  <span id="liveStatus">🔴 Live - Updating in real-time</span>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Stats Card -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <i class="bi bi-newspaper display-4 text-primary mb-2"></i>
        <div class="small text-muted">Total Articles</div>
        <div class="fw-bold fs-5" id="totalArticles">0</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <i class="bi bi-chat-dots display-4 text-success mb-2"></i>
        <div class="small text-muted">Positive Coverage</div>
        <div class="fw-bold fs-5 text-success" id="positiveCount">0%</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <i class="bi bi-graph-up display-4 text-warning mb-2"></i>
        <div class="small text-muted">Trending</div>
        <div class="fw-bold fs-5 text-warning" id="trendingCount">0 Topics</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <i class="bi bi-clock display-4 text-info mb-2"></i>
        <div class="small text-muted">Last Update</div>
        <div class="fw-bold fs-6 text-info" id="lastUpdate">Just now</div>
      </div>
    </div>
  </div>
</div>

<!-- News Grid -->
<div class="row g-3" id="newsContainer">
  <div class="col-12 text-center py-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-3 text-muted">Fetching World Cup news...</p>
  </div>
</div>

<style>
  @keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0.5; }
  }
  
  .news-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
  }
  
  .news-card:hover {
    border-left-color: #ffc107;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
  }
  
  .news-card.new {
    animation: slideIn 0.5s ease;
    border-left-color: #28a745;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
</style>

@endsection

@push('scripts')
<script>
  let lastUpdateTime = new Date();
  const apiEndpoint = '/api/v1/news/realtime/worldcup';
  const autoRefreshInterval = 30000; // 30 seconds
  
  // Initial load
  loadNews();
  
  // Auto refresh
  setInterval(loadNews, autoRefreshInterval);
  
  function loadNews() {
    fetch(apiEndpoint)
      .then(r => r.json())
      .then(data => {
        if (data.status === 'success') {
          renderNews(data.data);
          updateStats(data);
          lastUpdateTime = new Date();
          updateLastUpdateTime();
        }
      })
      .catch(e => {
        console.error('Error loading news:', e);
        document.getElementById('liveStatus').textContent = '⚠️ Update failed - retrying...';
      });
  }
  
  function renderNews(articles) {
    const container = document.getElementById('newsContainer');
    
    if (!articles || articles.length === 0) {
      container.innerHTML = '<div class="col-12 text-center py-5 text-muted"><p>No World Cup news available</p></div>';
      return;
    }
    
    container.innerHTML = articles.map((article, idx) => `
      <div class="col-12 col-md-6 col-lg-4 news-item" style="animation-delay: ${idx * 50}ms;">
        <div class="card h-100 news-card ${idx === 0 ? 'new' : ''}">
          ${article.image_url ? `<img src="${article.image_url}" class="card-img-top" style="height:150px;object-fit:cover" onerror="this.style.display='none'" alt="">` : ''}
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2 flex-wrap gap-2">
              <span class="badge bg-${article.sentiment === 'positive' ? 'success' : (article.sentiment === 'negative' ? 'danger' : 'secondary')}">
                ${article.sentiment_icon} ${article.sentiment.charAt(0).toUpperCase() + article.sentiment.slice(1)}
              </span>
              <small class="text-muted">${formatTime(article.published_at)}</small>
            </div>
            <h6 class="card-title fw-semibold" style="font-size:.95rem;line-height:1.4">${article.title}</h6>
            ${article.description ? `<p class="card-text text-muted small" style="line-height:1.5">${article.description.substring(0, 120)}...</p>` : ''}
            <div class="d-flex justify-content-between mt-auto mb-2">
              <small class="text-muted">${article.source_name}</small>
              <small class="fw-semibold text-${article.sentiment_score > 0 ? 'success' : (article.sentiment_score < 0 ? 'danger' : 'muted')}">
                ${article.sentiment_score.toFixed(1)}
              </small>
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 pb-3">
            <a href="${article.url}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm w-100">
              <i class="bi bi-box-arrow-up-right me-1"></i>Read More
            </a>
          </div>
        </div>
      </div>
    `).join('');
  }
  
  function updateStats(data) {
    document.getElementById('totalArticles').textContent = data.count || 0;
    
    // Calculate sentiments
    const positive = (data.data || []).filter(n => n.sentiment === 'positive').length;
    const total = data.count || 1;
    document.getElementById('positiveCount').textContent = Math.round((positive / total) * 100) + '%';
    document.getElementById('trendingCount').textContent = Math.round(total / 3) + ' Topics';
  }
  
  function updateLastUpdateTime() {
    const now = new Date();
    const diff = Math.round((now - lastUpdateTime) / 1000);
    
    let timeText = 'Just now';
    if (diff >= 60) timeText = Math.round(diff / 60) + ' min ago';
    else if (diff > 0) timeText = diff + ' sec ago';
    
    document.getElementById('lastUpdate').textContent = timeText;
  }
  
  function formatTime(isoString) {
    const date = new Date(isoString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return minutes + ' min ago';
    if (minutes < 1440) return Math.floor(minutes / 60) + ' hour ago';
    return Math.floor(minutes / 1440) + ' day ago';
  }
  
  function refreshNews() {
    const btn = document.getElementById('refreshBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i>Refreshing...';
    
    loadNews();
    
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Refresh Now';
    }, 1000);
  }
  
  // Update "last update" time every second
  setInterval(updateLastUpdateTime, 1000);
</script>

<style>
  .spin {
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
</style>
@endpush
