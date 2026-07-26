<?php $__env->startSection('title','Kelola Artikel'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
<li class="breadcrumb-item active">Artikel</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-newspaper me-2 text-info"></i>Kelola Artikel Analisis</h4>
  <a href="<?php echo e(route('admin.articles.create')); ?>" class="btn btn-primary btn-sm">
    <i class="bi bi-plus me-1"></i>Tulis Artikel
  </a>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Penulis</th><th>Views</th><th>Dibuat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td>
              <div class="fw-medium small"><?php echo e(Str::limit($art->title,50)); ?></div>
              <?php if($art->country_code): ?><span class="badge bg-light text-dark border" style="font-size:.62rem"><?php echo e($art->country_code); ?></span><?php endif; ?>
            </td>
            <td><span class="badge bg-info-subtle text-info small"><?php echo e($art->category_label); ?></span></td>
            <td><span class="badge bg-<?php echo e($art->status==='published'?'success':($art->status==='draft'?'warning':'secondary')); ?>"><?php echo e(ucfirst($art->status)); ?></span></td>
            <td class="small text-muted"><?php echo e($art->author?->name??'—'); ?></td>
            <td class="text-muted small"><?php echo e($art->views); ?></td>
            <td class="text-muted small"><?php echo e($art->created_at->format('d M Y')); ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?php echo e(route('admin.articles.edit',$art->id)); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.7rem;padding:2px 8px">Edit</a>
                <form method="POST" action="<?php echo e(route('admin.articles.delete',$art->id)); ?>" onsubmit="return confirm('Hapus artikel?')">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-xs btn-outline-danger" style="font-size:.7rem;padding:2px 8px"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">
            Belum ada artikel. <a href="<?php echo e(route('admin.articles.create')); ?>">Tulis artikel pertama</a>
          </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer"><?php echo e($articles->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\articles.blade.php ENDPATH**/ ?>