<?php $__env->startSection('title','Kelola Users'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
<li class="breadcrumb-item active">Users</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Kelola Users</h4>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Terdaftar</th><th class="text-center">Aksi</th></tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="text-muted small"><?php echo e($u->id); ?></td>
            <td><div class="d-flex align-items-center gap-2">
              <img src="<?php echo e($u->avatar_url); ?>" width="32" height="32" class="rounded-circle">
              <div class="fw-medium small"><?php echo e($u->name); ?></div>
            </div></td>
            <td class="small text-muted"><?php echo e($u->email); ?></td>
            <td><span class="badge bg-<?php echo e($u->role==='admin'?'danger':'primary'); ?>"><?php echo e(ucfirst($u->role)); ?></span></td>
            <td><span class="badge bg-<?php echo e($u->is_active?'success':'secondary'); ?>"><?php echo e($u->is_active?'Aktif':'Nonaktif'); ?></span></td>
            <td class="text-muted small"><?php echo e($u->created_at->format('d M Y')); ?></td>
            <td class="text-center">
              <?php if($u->id!==auth()->id()): ?>
              <div class="d-flex gap-1 justify-content-center">
                <select class="form-select form-select-sm" style="width:85px" onchange="chRole(<?php echo e($u->id); ?>,this.value)">
                  <option value="user" <?php echo e($u->role==='user'?'selected':''); ?>>User</option>
                  <option value="admin" <?php echo e($u->role==='admin'?'selected':''); ?>>Admin</option>
                </select>
                <form method="POST" action="<?php echo e(route('admin.users.delete',$u->id)); ?>" onsubmit="return confirm('Hapus user ini?')">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </div>
              <?php else: ?><span class="badge bg-info small">You</span><?php endif; ?>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada user</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function chRole(id,role){
  if(!confirm(`Ubah role menjadi ${role}?`))return location.reload();
  fetch(`/admin/users/${id}/role`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},body:JSON.stringify({role})})
  .then(r=>r.json()).then(d=>{d.success?location.reload():alert(d.message||'Gagal');});
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\admin\users.blade.php ENDPATH**/ ?>