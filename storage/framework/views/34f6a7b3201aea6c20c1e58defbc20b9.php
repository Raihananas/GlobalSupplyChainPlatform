<?php $__env->startSection('title','Admin Dashboard'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Admin</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2 text-primary"></i>Admin Dashboard</h4>
</div>

<div class="row g-3 mb-4">
  <?php $__currentLoopData = [
    ['l'=>'Total Users','v'=>$stats['users'],'i'=>'bi-people','c'=>'primary'],
    ['l'=>'Active Users','v'=>$stats['active_users'],'i'=>'bi-person-check','c'=>'success'],
    ['l'=>'Pelabuhan','v'=>$stats['ports'],'i'=>'bi-anchor','c'=>'dark'],
    ['l'=>'Artikel','v'=>$stats['articles'],'i'=>'bi-newspaper','c'=>'info'],
    ['l'=>'API Calls Hari Ini','v'=>$stats['api_calls_today'],'i'=>'bi-cloud-arrow-up','c'=>'warning'],
    ['l'=>'API Errors','v'=>$stats['api_errors'],'i'=>'bi-x-circle','c'=>'danger'],
    ['l'=>'Berita Hari Ini','v'=>$stats['news_today'],'i'=>'bi-newspaper','c'=>'secondary'],
    ['l'=>'High Risk Countries','v'=>$stats['high_risk'],'i'=>'bi-exclamation-triangle','c'=>'danger'],
  ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="col-6 col-md-3">
    <div class="card">
      <div class="card-body py-3 d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:42px;height:42px;background:#f0f2f5;flex-shrink:0">
          <i class="bi <?php echo e($s['i']); ?> text-<?php echo e($s['c']); ?> fs-5"></i>
        </div>
        <div><div class="fw-bold fs-5"><?php echo e($s['v']); ?></div><div class="text-muted" style="font-size:.76rem"><?php echo e($s['l']); ?></div></div>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="card mb-4">
  <div class="card-header fw-semibold"><i class="bi bi-lightning me-2 text-primary"></i>Quick Actions</div>
  <div class="card-body d-flex flex-wrap gap-2">
    <a href="<?php echo e(route('admin.users')); ?>"     class="btn btn-outline-primary btn-sm"><i class="bi bi-people me-1"></i>Kelola Users</a>
    <a href="<?php echo e(route('admin.ports')); ?>"     class="btn btn-outline-dark btn-sm"><i class="bi bi-anchor me-1"></i>Kelola Ports</a>
    <a href="<?php echo e(route('admin.articles')); ?>"  class="btn btn-outline-info btn-sm"><i class="bi bi-newspaper me-1"></i>Kelola Artikel</a>
    <a href="<?php echo e(route('admin.settings')); ?>"  class="btn btn-outline-secondary btn-sm"><i class="bi bi-gear me-1"></i>Pengaturan</a>
    <a href="<?php echo e(route('countries.index')); ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-globe me-1"></i>Country Dashboard</a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header fw-semibold small"><i class="bi bi-clock-history me-2 text-primary"></i>Activity Log Terbaru</div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light"><tr><th>User</th><th>Aksi</th><th>Waktu</th></tr></thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="small"><?php echo e($log->user?->name??'Guest'); ?></td>
              <td><span class="badge bg-light text-dark border small"><?php echo e($log->action); ?></span></td>
              <td class="text-muted" style="font-size:.74rem"><?php echo e($log->created_at->diffForHumans()); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="3" class="text-center text-muted py-3 small">Tidak ada log</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header fw-semibold small"><i class="bi bi-cloud me-2 text-info"></i>API Call Log Terbaru</div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light"><tr><th>API</th><th>Endpoint</th><th>Status</th><th>ms</th></tr></thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentApiLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><span class="badge bg-primary-subtle text-primary small"><?php echo e($log->api_name); ?></span></td>
              <td class="small text-muted" style="max-width:130px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis"><?php echo e($log->endpoint); ?></td>
              <td><?php if($log->success): ?><span class="badge bg-success-subtle text-success" style="font-size:.65rem">✓<?php echo e($log->response_code); ?></span><?php else: ?><span class="badge bg-danger-subtle text-danger" style="font-size:.65rem">✗<?php echo e($log->response_code); ?></span><?php endif; ?></td>
              <td class="text-muted small"><?php echo e($log->response_time_ms); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" class="text-center text-muted py-3 small">Tidak ada log API</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>