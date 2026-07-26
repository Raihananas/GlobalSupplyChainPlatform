
<?php $__env->startSection('title','Breaking News - Live Alerts'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('news.index')); ?>">News Intelligence</a></li>
<li class="breadcrumb-item active">🔴 Breaking News</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-0 fw-bold"><i class="bi bi-exclamation-circle text-danger me-2"></i>Breaking News - Urgent Alerts</h3>
    <small class="text-muted">Critical updates from around the world</small>
  </div>
  <button class="btn btn-danger" id="refreshBtn" onclick="refreshNews()">
    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Now
  </button>
</div>

<!-- Critical Alert -->
<div class="alert alert-danger alert-dismissible fade show mb-4" id="criticalAlert">
  <div class="d-flex align-items-center">
    <div class="blinking-dot" style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; margin-right: 10px; animation: pulse 1s infinite;"></div>
    <span id="alertStatus">🔴 LIVE MONITORING - Breaking News Feed Active</span>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Priority Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card border-danger border-2">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-danger fw-bold">CRITICAL ALERTS</small>
            <div class="fs-5 fw-bold" id="criticalCount">0</div>
          </div>
          <i class="bi bi-exclamation-triangle display-4 text-danger opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-warning border-2">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-warning fw-bold">URGENT UPDATES</small>
            <div class="fs-5 fw-bold text-warning" id="urgentCount">0</div>
          </div>
          <i class="bi bi-clock-history display-4 text-warning opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-info border-2">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-info fw-bold">LAST UPDATE</small>
            <div class="fs-6 fw-bold text-info" id="lastUpdate">Just now</div>
          </div>
          <i class="bi bi-clock display-4 text-info opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- News Timeline -->
<div id="newsContainer" class="timeline-container">
  <div class="col-12 text-center py-5">
    <div class="spinner-border text-danger" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-3 text-muted">Loading breaking news...</p>
  </div>
</div>

<style>
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
  }
  
  .timeline-container {
    position: relative;
  }
  
  .news-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
  }
  
  .news-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 10px;
    height: 10px;
    background: #dc3545;
    border-radius: 50%;
    border: 2px solid white;
  }
  
  .news-item.new::before {
    animation: pulse 1s infinite;
  }
  
  .news-item::after {
    content: '';
    position: absolute;
    left: 4px;
    top: 15px;
    width: 2px;
    height: calc(100% + 20px);
    background: #dc3545;
  }
  
  .news-item:last-child::after {
    display: none;
  }
  
  .card-highlight {
    border-left: 4px solid #dc3545;
    animation: highlightSlideIn 0.5s ease;
  }
  
  @keyframes highlightSlideIn {
    from {
      opacity: 0;
      transform: translateX(-10px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  let lastUpdateTime = new Date();
  const apiEndpoint = '/api/v1/news/realtime/breaking';
  const autoRefreshInterval = 15000; // 15 seconds for breaking news
  
  // Initial load
  loadNews();
  
  // Auto refresh - more frequent for breaking news
  setInterval(loadNews, autoRefreshInterval);
  
  function loadNews() {
    fetch(apiEndpoint)
      .then(r => r.json())
      .then(data => {
        if (data.status === 'success') {
          renderNews(data.data);
          updateStats(data.data);
          lastUpdateTime = new Date();
          updateLastUpdateTime();
        }
      })
      .catch(e => {
        console.error('Error loading news:', e);
        document.getElementById('alertStatus').textContent = '⚠️ Connection issue - retrying...';
      });
  }
  
  function renderNews(articles) {
    const container = document.getElementById('newsContainer');
    
    if (!articles || articles.length === 0) {
      container.innerHTML = '<div class="col-12 text-center py-5 text-muted"><p>No breaking news available</p></div>';
      return;
    }
    
    container.innerHTML = articles.map((article, idx) => `
      <div class="news-item ${idx === 0 ? 'new' : ''}" style="animation-delay: ${idx * 50}ms;">
        <div class="card h-100 card-highlight shadow-sm">
          ${article.image_url ? `<img src="${article.image_url}" class="card-img-top" style="height:200px;object-fit:cover" onerror="this.style.display='none'" alt="">` : ''}
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
              <div class="flex-grow-1">
                <span class="badge bg-danger">🔴 BREAKING</span>
                <span class="badge bg-${article.sentiment === 'negative' ? 'danger' : (article.sentiment === 'positive' ? 'success' : 'warning')}" style="margin-left: 5px;">
                  ${article.sentiment_icon} ${article.sentiment.charAt(0).toUpperCase() + article.sentiment.slice(1)}
                </span>
              </div>
              <small class="text-muted fw-bold">${formatTime(article.published_at)}</small>
            </div>
            
            <h5 class="card-title fw-bold text-dark mb-3" style="line-height:1.5">${article.title}</h5>
            
            ${article.description ? `<p class="card-text text-muted" style="line-height:1.6; margin-bottom: 15px;">${article.description}</p>` : ''}
            
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
              <small class="text-secondary fw-semibold">${article.source_name}</small>
              <span class="badge bg-${article.sentiment_score < -5 ? 'danger' : (article.sentiment_score > 5 ? 'success' : 'secondary')}">
                Impact: ${Math.abs(article.sentiment_score).toFixed(1)}
              </span>
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 pb-3">
            <a href="${article.url}" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm w-100">
              <i class="bi bi-box-arrow-up-right me-1"></i>Read Full Report
            </a>
          </div>
        </div>
      </div>
    `).join('');
  }
  
  function updateStats(articles) {
    const negative = (articles || []).filter(n => n.sentiment === 'negative').length;
    const total = articles ? articles.length : 1;
    
    document.getElementById('criticalCount').textContent = negative > 0 ? negative : total;
    document.getElementById('urgentCount').textContent = Math.round(total / 2);
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/news/breaking.blade.php ENDPATH**/ ?>