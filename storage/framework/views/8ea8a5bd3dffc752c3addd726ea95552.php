
<?php $__env->startSection('title','Real-time News Dashboard'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">⚡ Real-time News</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-0 fw-bold"><i class="bi bi-lightning-fill text-warning me-2"></i>Real-time News Dashboard</h3>
    <small class="text-muted">Live updates across all topics</small>
  </div>
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-outline-secondary active" onclick="setAutoRefresh(true)">
      <i class="bi bi-play-circle me-1"></i>Auto-Refresh
    </button>
    <button type="button" class="btn btn-outline-secondary" onclick="setAutoRefresh(false)">
      <i class="bi bi-pause-circle me-1"></i>Pause
    </button>
  </div>
</div>

<!-- Overall Statistics -->
<div class="row g-3 mb-4">
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="card-body text-white">
        <i class="bi bi-newspaper display-5 mb-2"></i>
        <div class="small">Total News</div>
        <div class="fw-bold fs-5" id="totalNews">0</div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
      <div class="card-body text-white">
        <i class="bi bi-chat-dots display-5 mb-2"></i>
        <div class="small">Positive</div>
        <div class="fw-bold fs-5" id="positiveNews">0</div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
      <div class="card-body text-white">
        <i class="bi bi-dash-circle display-5 mb-2"></i>
        <div class="small">Neutral</div>
        <div class="fw-bold fs-5" id="neutralNews">0</div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
      <div class="card-body text-white">
        <i class="bi bi-exclamation-triangle display-5 mb-2"></i>
        <div class="small">Negative</div>
        <div class="fw-bold fs-5" id="negativeNews">0</div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
      <div class="card-body text-white">
        <i class="bi bi-graph-up display-5 mb-2"></i>
        <div class="small">Avg Score</div>
        <div class="fw-bold fs-5" id="avgScore">0.0</div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center" style="background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);">
      <div class="card-body text-white">
        <i class="bi bi-clock display-5 mb-2"></i>
        <div class="small">Last Updated</div>
        <div class="fw-bold fs-6" id="lastUpdated">Now</div>
      </div>
    </div>
  </div>
</div>

<!-- Trending and Impactful -->
<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header fw-bold bg-light">
        <i class="bi bi-fire text-danger me-2"></i>Trending News
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto;">
        <div id="trendingContainer" class="list-group list-group-flush">
          <p class="text-muted text-center py-5">Loading...</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header fw-bold bg-light">
        <i class="bi bi-star text-warning me-2"></i>High Impact
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto;">
        <div id="impactfulContainer" class="list-group list-group-flush">
          <p class="text-muted text-center py-5">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- News by Topics -->
<div class="card mb-4">
  <div class="card-header fw-bold">
    <i class="bi bi-collection me-2"></i>News by Topic
  </div>
  <div class="card-body">
    <div class="row" id="topicsContainer">
      <p class="text-muted text-center py-5 col-12">Loading topics...</p>
    </div>
  </div>
</div>

<style>
  .topic-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 8px;
    margin: 5px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: transform 0.2s;
  }
  
  .topic-badge:hover {
    transform: translateY(-2px);
  }
  
  .news-item-trending {
    padding: 12px;
    border-left: 3px solid #ff6b6b;
    transition: all 0.3s;
  }
  
  .news-item-trending:hover {
    background-color: rgba(255, 107, 107, 0.1);
    border-left-color: #ff4757;
  }
  
  .news-item-trending h6 {
    margin-bottom: 5px;
    font-size: 0.95rem;
  }
  
  .news-item-trending small {
    display: block;
    margin-top: 5px;
  }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  let autoRefreshEnabled = true;
  let lastUpdateTime = new Date();
  
  const endpoints = {
    stats: '/api/v1/news/realtime/stats',
    trending: '/api/v1/news/realtime/trending',
    world: '/api/v1/news/realtime/world',
    byTopics: '/api/v1/news/realtime/topic'
  };
  
  // Initial load
  loadDashboard();
  
  // Auto refresh
  setInterval(() => {
    if (autoRefreshEnabled) {
      loadDashboard();
    }
  }, 30000); // 30 seconds
  
  function loadDashboard() {
    Promise.all([
      fetch('/api/v1/news/realtime/stats').then(r => r.json()),
      fetch('/api/v1/news/realtime/trending').then(r => r.json()),
      fetch('/api/v1/news/realtime/world?limit=5').then(r => r.json())
    ])
    .then(([stats, trending, world]) => {
      if (stats.status === 'success') updateStats(stats.data);
      if (trending.status === 'success') {
        renderTrending(trending.data);
        renderImpactful(trending.data);
      }
      if (world.status === 'success') renderTopics(world.data);
      lastUpdateTime = new Date();
      updateLastUpdatedTime();
    })
    .catch(e => console.error('Dashboard load error:', e));
  }
  
  function updateStats(stats) {
    document.getElementById('totalNews').textContent = stats.total_articles || 0;
    document.getElementById('positiveNews').textContent = stats.positive_count || 0;
    document.getElementById('neutralNews').textContent = stats.neutral_count || 0;
    document.getElementById('negativeNews').textContent = stats.negative_count || 0;
    document.getElementById('avgScore').textContent = (stats.avg_sentiment_score || 0).toFixed(1);
  }
  
  function renderTrending(articles) {
    const container = document.getElementById('trendingContainer');
    
    if (!articles || articles.length === 0) {
      container.innerHTML = '<p class="text-muted text-center py-3">No trending news</p>';
      return;
    }
    
    container.innerHTML = articles.slice(0, 5).map(article => `
      <div class="news-item-trending">
        <h6 class="text-dark">${article.title.substring(0, 80)}...</h6>
        <div class="d-flex justify-content-between">
          <small class="text-muted">${article.source_name}</small>
          <small class="fw-bold text-${article.sentiment === 'positive' ? 'success' : (article.sentiment === 'negative' ? 'danger' : 'warning')}">
            ${article.sentiment_icon}
          </small>
        </div>
      </div>
    `).join('');
  }
  
  function renderImpactful(articles) {
    const container = document.getElementById('impactfulContainer');
    
    const impactful = (articles || [])
      .sort((a, b) => Math.abs(b.sentiment_score || 0) - Math.abs(a.sentiment_score || 0))
      .slice(0, 5);
    
    if (impactful.length === 0) {
      container.innerHTML = '<p class="text-muted text-center py-3">No high-impact news</p>';
      return;
    }
    
    container.innerHTML = impactful.map(article => `
      <div class="news-item-trending" style="border-left-color: ${article.sentiment_score > 0 ? '#51cf66' : '#ff6b6b'};">
        <h6 class="text-dark">${article.title.substring(0, 80)}...</h6>
        <div class="d-flex justify-content-between">
          <small class="text-muted">${article.source_name}</small>
          <small class="fw-bold text-${article.sentiment_score > 0 ? 'success' : 'danger'}">
            Impact: ${Math.abs(article.sentiment_score).toFixed(1)}
          </small>
        </div>
      </div>
    `).join('');
  }
  
  function renderTopics(articles) {
    const container = document.getElementById('topicsContainer');
    const topics = {};
    
    (articles || []).forEach(article => {
      const topic = article.topic || 'general';
      if (!topics[topic]) topics[topic] = 0;
      topics[topic]++;
    });
    
    const colors = ['primary', 'success', 'danger', 'warning', 'info', 'secondary'];
    
    container.innerHTML = Object.entries(topics)
      .map(([topic, count], idx) => `
        <div class="col-md-6 col-lg-4">
          <div class="topic-badge bg-${colors[idx % colors.length]} text-white">
            ${topic.charAt(0).toUpperCase() + topic.slice(1)}: <strong>${count}</strong>
          </div>
        </div>
      `)
      .join('');
  }
  
  function updateLastUpdatedTime() {
    const diff = Math.round((new Date() - lastUpdateTime) / 1000);
    let text = 'Just now';
    
    if (diff >= 60) text = Math.round(diff / 60) + ' min ago';
    else if (diff > 0) text = diff + ' sec ago';
    
    document.getElementById('lastUpdated').textContent = text;
  }
  
  function setAutoRefresh(enabled) {
    autoRefreshEnabled = enabled;
    const buttons = document.querySelectorAll('.btn-group .btn');
    buttons[0].classList.toggle('active', enabled);
    buttons[1].classList.toggle('active', !enabled);
    
    if (enabled) loadDashboard();
  }
  
  // Update last updated time every second
  setInterval(updateLastUpdatedTime, 1000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/news/realtime-dashboard.blade.php ENDPATH**/ ?>