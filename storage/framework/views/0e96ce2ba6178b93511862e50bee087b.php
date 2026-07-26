<?php $__env->startSection('title','News Intelligence'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">News Intelligence</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0 fw-bold"><i class="bi bi-newspaper me-2 text-primary"></i>News Intelligence</h4>
  <small class="text-muted">GNews API + Lexicon Sentiment Analysis + Real-time Updates</small>
</div>

<ul class="nav nav-pills mb-4" id="newsTabs">
  <?php $icons=['logistics'=>'bi-truck','trade'=>'bi-box-seam','shipping'=>'bi-anchor','economy'=>'bi-graph-up','geopolitics'=>'bi-globe2','breaking'=>'bi-exclamation-circle','worldcup'=>'bi-trophy']; ?>
  <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <li class="nav-item">
    <a class="nav-link <?php echo e($topic===$t?'active':''); ?>" href="<?php echo e(route('news.topic',$t)); ?>" data-topic="<?php echo e($t); ?>">
      <i class="bi <?php echo e($icons[$t]??'bi-newspaper'); ?> me-1"></i><?php echo e($label); ?>

    </a>
  </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <li class="nav-item ms-auto">
    <a href="<?php echo e(route('news.realtime-dashboard')); ?>" class="nav-link">
      <i class="bi bi-lightning-fill me-1 text-warning"></i>Live Dashboard
    </a>
  </li>
</ul>

<div class="card mb-4">
  <div class="card-header fw-semibold"><i class="bi bi-emoji-neutral me-2 text-primary"></i>Sentiment Analysis — <?php echo e(ucfirst($topic)); ?></div>
  <div class="card-body">
    <div class="row g-3 align-items-center">
      <div class="col-md-3"><canvas id="sentPie" height="170"></canvas></div>
      <div class="col-md-9">
        <div class="row g-3 mb-3">
          <div class="col-4 text-center">
            <div class="p-3 rounded border border-success border-opacity-25" style="background: rgba(16, 185, 129, 0.15);">
              <div style="font-size:1.8rem;font-weight:700;color:#34d399;"><?php echo e($sentimentSummary['positive']??0); ?>%</div>
              <div class="small text-success fw-medium">😊 Positive</div>
            </div>
          </div>
          <div class="col-4 text-center">
            <div class="p-3 rounded border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.05);">
              <div style="font-size:1.8rem;font-weight:700;color:#f8fafc;"><?php echo e($sentimentSummary['neutral']??0); ?>%</div>
              <div class="small text-light fw-medium">😐 Neutral</div>
            </div>
          </div>
          <div class="col-4 text-center">
            <div class="p-3 rounded border border-danger border-opacity-25" style="background: rgba(239, 68, 68, 0.15);">
              <div style="font-size:1.8rem;font-weight:700;color:#f87171;"><?php echo e($sentimentSummary['negative']??0); ?>%</div>
              <div class="small text-danger fw-medium">😟 Negative</div>
            </div>
          </div>
        </div>
        <div class="row g-2">
          <div class="col-6"><div class="small text-muted">Total Berita</div><div class="fw-bold"><?php echo e($sentimentSummary['total']??0); ?> artikel</div></div>
          <div class="col-6"><div class="small text-muted">Dominan</div>
            <?php $dom=$sentimentSummary['dominant']??'neutral'; ?>
            <span class="badge bg-<?php echo e($dom==='positive'?'success':($dom==='negative'?'danger':'secondary')); ?> fs-6"><?php echo e(ucfirst($dom)); ?></span>
          </div>
          <div class="col-6"><div class="small text-muted">Avg Score</div>
            <div class="fw-bold <?php echo e(($sentimentSummary['avg_score']??0)>0?'text-success':(($sentimentSummary['avg_score']??0)<0?'text-danger':'text-muted')); ?>"><?php echo e(number_format($sentimentSummary['avg_score']??0,1)); ?></div>
          </div>
          <div class="col-6"><div class="small text-muted">News Risk</div>
            <div class="fw-bold text-<?php echo e(($sentimentSummary['negative']??0)>50?'danger':(($sentimentSummary['negative']??0)>30?'warning':'success')); ?>">
              <?php echo e(($sentimentSummary['negative']??0)>50?'🔴 High':(($sentimentSummary['negative']??0)>30?'⚠️ Medium':'✅ Low')); ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Real-time update indicator -->
<div id="liveUpdateStatus" class="alert alert-info alert-dismissible fade show mb-3" style="display:none;">
  <i class="bi bi-lightning-fill me-2"></i><span id="updateMessage">Fetching latest news...</span>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="row g-3">
  <?php $__empty_1 = true; $__currentLoopData = $newsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 news-card" style="transition:transform .15s" onmouseenter="this.style.transform='translateY(-3px)'" onmouseleave="this.style.transform=''" data-news-url="<?php echo e($news->url); ?>">
      <?php if($news->image_url): ?>
      <img src="<?php echo e($news->image_url); ?>" class="card-img-top" style="height:150px;object-fit:cover" onerror="this.style.display='none'" alt="">
      <?php endif; ?>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <span class="badge bg-<?php echo e($news->sentiment_badge_class); ?>"><?php echo e($news->sentiment_icon); ?> <?php echo e(ucfirst($news->sentiment)); ?></span>
          <small class="text-muted"><?php echo e($news->time_ago); ?></small>
        </div>
        <h6 class="card-title fw-semibold" style="font-size:.87rem;line-height:1.4"><?php echo e(Str::limit($news->title,90)); ?></h6>
        <?php if($news->description): ?><p class="card-text text-muted small" style="line-height:1.5"><?php echo e(Str::limit($news->description,110)); ?></p><?php endif; ?>
        <div class="d-flex justify-content-between mt-auto">
          <small class="text-muted"><?php echo e($news->source_name); ?></small>
          <small class="<?php echo e($news->sentiment_score>0?'text-success':($news->sentiment_score<0?'text-danger':'text-muted')); ?> fw-semibold"><?php echo e(number_format($news->sentiment_score,1)); ?></small>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0 pb-3">
        <a href="<?php echo e($news->url); ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm w-100">
          <i class="bi bi-box-arrow-up-right me-1"></i>Baca Selengkapnya
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <div class="col-12 text-center py-5 text-muted">
    <i class="bi bi-newspaper display-4 d-block mb-3 opacity-25"></i>
    <p>Tidak ada berita untuk topik <b><?php echo e($topic); ?></b>.</p>
    <p class="small">Tambahkan <code>GNEWS_API_KEY</code> di <code>.env</code> untuk berita real-time.</p>
  </div>
  <?php endif; ?>
</div>
<div class="mt-4 d-flex justify-content-center"><?php echo e($newsList->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Chart initialization
new Chart(document.getElementById('sentPie').getContext('2d'),{
  type:'doughnut',
  data:{labels:['Positive','Neutral','Negative'],datasets:[{data:[<?php echo e($sentimentSummary['positive']??0); ?>,<?php echo e($sentimentSummary['neutral']??0); ?>,<?php echo e($sentimentSummary['negative']??0); ?>],backgroundColor:['#198754','#6c757d','#dc3545'],borderWidth:3,borderColor:'#fff'}]},
  options:{responsive:true,cutout:'65%',plugins:{legend:{position:'bottom',labels:{font:{size:11}}}}}
});

// Real-time update functionality
(function() {
  const currentTopic = '<?php echo e($topic); ?>';
  const updateInterval = 60000; // 60 seconds
  
  // Show live update indicator
  function showUpdateStatus(message) {
    const statusEl = document.getElementById('liveUpdateStatus');
    if(statusEl) {
      document.getElementById('updateMessage').textContent = message;
      statusEl.style.display = 'block';
      setTimeout(() => statusEl.style.display = 'none', 5000);
    }
  }
  
  // Auto-refresh news every minute
  setInterval(() => {
    if (currentTopic && currentTopic !== 'general') {
      fetch(`/api/v1/news/realtime/topic/${currentTopic}`)
        .then(r => r.json())
        .then(data => {
          if(data.status === 'success' && data.data.length > 0) {
            showUpdateStatus(`✅ Updated with ${data.count} latest articles at ${new Date().toLocaleTimeString()}`);
          }
        })
        .catch(e => console.error('Update error:', e));
    }
  }, updateInterval);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/news/index.blade.php ENDPATH**/ ?>