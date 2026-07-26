<?php $__env->startSection('title','Pengaturan Sistem'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
<li class="breadcrumb-item active">Pengaturan</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-gear me-2 text-secondary"></i>Pengaturan Sistem</h4>
</div>

<form method="POST" action="<?php echo e(route('admin.settings.save')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>
  <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupSettings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="card mb-4">
    <div class="card-header fw-semibold text-capitalize">
      <i class="bi bi-sliders me-2 text-primary"></i>
      <?php echo e(str_replace('_',' ', ucwords($group,'_'))); ?>

      <?php if($group==='risk_weights'): ?>
      <small class="text-muted ms-2 fw-normal">(Total harus = 100%)</small>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <?php $__currentLoopData = $groupSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6">
          <label class="form-label fw-medium small">
            <?php echo e($setting->key); ?>

            <?php if($setting->description): ?><i class="bi bi-question-circle text-muted ms-1" title="<?php echo e($setting->description); ?>"></i><?php endif; ?>
          </label>
          <?php if($setting->type==='json'): ?>
          <textarea name="<?php echo e($setting->key); ?>" class="form-control form-control-sm font-monospace" rows="2"><?php echo e($setting->value); ?></textarea>
          <?php elseif($setting->type==='boolean'): ?>
          <select name="<?php echo e($setting->key); ?>" class="form-select form-select-sm">
            <option value="true"  <?php echo e($setting->value==='true'?'selected':''); ?>>Ya</option>
            <option value="false" <?php echo e($setting->value!=='true'?'selected':''); ?>>Tidak</option>
          </select>
          <?php else: ?>
          <input type="<?php echo e(in_array($setting->type,['integer','decimal'])?'number':'text'); ?>"
            name="<?php echo e($setting->key); ?>" class="form-control form-control-sm"
            value="<?php echo e($setting->value); ?>"
            step="<?php echo e($setting->type==='decimal'?'0.01':'1'); ?>">
          <?php endif; ?>
          <?php if($setting->description): ?>
          <div class="text-muted" style="font-size:.7rem;margin-top:2px"><?php echo e($setting->description); ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <!-- Site Branding Configuration Card -->
  <div class="card mb-4">
    <div class="card-header fw-semibold">
      <i class="bi bi-sliders me-2 text-primary"></i>
      Site Branding Configuration
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-medium small">Browser Tab Icon (Favicon)</label>
          <div class="d-flex align-items-center gap-3 mb-2">
            <img src="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(time()); ?>" alt="Favicon Preview" style="width: 32px; height: 32px;" class="border p-1 rounded bg-dark">
            <input type="file" name="favicon" class="form-control form-control-sm" accept="image/x-icon,image/png">
          </div>
          <small class="text-muted d-block mt-1">Recommended: .ico or .png format (16x16 or 32x32 pixels, Max 4MB).</small>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-medium small">Application Logo</label>
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="border rounded bg-dark d-flex align-items-center justify-content-center p-2" style="width: 48px; height: 48px;">
              <?php if(file_exists(public_path('uploads/site/logo.png'))): ?>
                <img src="<?php echo e(asset('uploads/site/logo.png')); ?>?v=<?php echo e(time()); ?>" alt="Logo Preview" style="max-height: 100%; max-width: 100%; object-fit: contain;">
              <?php else: ?>
                <i class="bi bi-globe2 text-white fs-4"></i>
              <?php endif; ?>
            </div>
            <input type="file" name="logo" class="form-control form-control-sm" accept="image/png,image/jpeg,image/svg+xml">
          </div>
          <small class="text-muted d-block mt-1">Recommended: Transparent .png or .svg format (Max 4MB).</small>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary fw-semibold">
      <i class="bi bi-save me-2"></i>Simpan Semua Pengaturan
    </button>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\settings.blade.php ENDPATH**/ ?>