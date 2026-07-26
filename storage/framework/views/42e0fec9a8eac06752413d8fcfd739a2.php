<?php $__env->startSection('title','Kelola Ports'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
<li class="breadcrumb-item active">Ports</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-anchor me-2"></i>Kelola Dataset Pelabuhan</h4>
  <span class="badge bg-secondary"><?php echo e($ports->total()); ?> pelabuhan</span>
</div>
<div class="card mb-3"><div class="card-body py-2">
  <input type="text" id="psearch" class="form-control" placeholder="Cari nama atau kode pelabuhan...">
</div></div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive" style="max-height:580px;overflow-y:auto">
      <table class="table table-sm table-hover mb-0">
        <thead class="table-light sticky-top">
          <tr><th>Pelabuhan</th><th>Kode</th><th>Negara</th><th>Region</th><th>Ukuran</th><th>Kongesti</th><th class="text-center">Aksi</th></tr>
        </thead>
        <tbody id="pTbody">
          <?php $__empty_1 = true; $__currentLoopData = $ports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr data-name="<?php echo e(strtolower($p->port_name)); ?>" data-code="<?php echo e(strtolower($p->port_code)); ?>">
            <td class="fw-medium small"><?php echo e($p->port_name); ?></td>
            <td><span class="badge bg-light text-dark border small"><?php echo e($p->port_code); ?></span></td>
            <td class="small"><?php echo e($p->country_name); ?></td>
            <td class="small text-muted"><?php echo e($p->province_region); ?></td>
            <td><span class="badge bg-secondary small"><?php echo e($p->harbor_size_label); ?></span></td>
            <td><span class="badge bg-<?php echo e($p->congestion_badge_class); ?>" style="font-size:.67rem"><?php echo e(ucfirst($p->congestion_level)); ?> (<?php echo e(round($p->congestion_score)); ?>)</span></td>
            <td class="text-center">
              <form method="POST" action="<?php echo e(route('admin.ports.delete',$p->id)); ?>" onsubmit="return confirm('Hapus pelabuhan ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-xs btn-outline-danger" style="font-size:.7rem;padding:2px 8px"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer"><?php echo e($ports->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('psearch').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#pTbody tr').forEach(r=>{
    r.style.display=!q||r.dataset.name?.includes(q)||r.dataset.code?.includes(q)?'':'none';
  });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\ports.blade.php ENDPATH**/ ?>