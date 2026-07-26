<?php $__env->startSection('title', $article ? 'Edit Artikel' : 'Tulis Artikel'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.articles')); ?>">Artikel</a></li>
<li class="breadcrumb-item active"><?php echo e($article ? 'Edit' : 'Baru'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:800px">
  <div class="card-header fw-semibold">
    <i class="bi bi-<?php echo e($article?'pencil':'plus'); ?> me-2 text-primary"></i>
    <?php echo e($article ? 'Edit Artikel' : 'Tulis Artikel Baru'); ?>

  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo e($action); ?>">
      <?php echo csrf_field(); ?>
      <?php if($article): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

      <div class="mb-3">
        <label class="form-label fw-medium">Judul <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
          value="<?php echo e(old('title', $article?->title)); ?>" required>
        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
          <select name="category" class="form-select" required>
            <?php $__currentLoopData = ['risk_analysis'=>'Risk Analysis','market_update'=>'Market Update','logistics'=>'Logistics','geopolitics'=>'Geopolitics','economy'=>'Economy']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($val); ?>" <?php echo e(old('category',$article?->category)===$val?'selected':''); ?>><?php echo e($lbl); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select" required>
            <option value="draft"     <?php echo e(old('status',$article?->status)==='draft'?'selected':''); ?>>Draft</option>
            <option value="published" <?php echo e(old('status',$article?->status)==='published'?'selected':''); ?>>Published</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-medium">Terkait Negara</label>
          <select name="country_code" class="form-select">
            <option value="">-- Semua Negara --</option>
            <?php $__currentLoopData = App\Models\Country::active()->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c->code); ?>" <?php echo e(old('country_code',$article?->country_code)===$c->code?'selected':''); ?>>
              <?php echo e($c->flag_emoji); ?> <?php echo e($c->name); ?>

            </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-medium">Ringkasan</label>
        <textarea name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat..."><?php echo e(old('excerpt', $article?->excerpt)); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-medium">Isi Artikel <span class="text-danger">*</span></label>
        <textarea name="body" class="form-control <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="14" required><?php echo e(old('body', $article?->body)); ?></textarea>
        <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary fw-semibold">
          <i class="bi bi-save me-2"></i><?php echo e($article ? 'Simpan Perubahan' : 'Publikasikan'); ?>

        </button>
        <a href="<?php echo e(route('admin.articles')); ?>" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\articles_form.blade.php ENDPATH**/ ?>