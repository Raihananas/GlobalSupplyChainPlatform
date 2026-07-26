<?php $__env->startSection('title','Favorite Monitoring List'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Watchlist</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-star me-2 text-warning"></i>Favorite Monitoring List</h4>
  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus me-1"></i>Tambah Negara
  </button>
</div>

<?php if($watchlist->isEmpty()): ?>
<div class="text-center py-5 text-muted">
  <i class="bi bi-star display-3 d-block mb-3 opacity-25"></i>
  <h5>Watchlist Kosong</h5>
  <p class="small">Tambahkan negara yang ingin Anda pantau secara rutin.</p>
  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus me-2"></i>Tambah Negara Pertama
  </button>
</div>
<?php else: ?>
<div class="row g-3">
  <?php $__currentLoopData = $watchlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php $rs=$riskScores[$item->country_code]??null; ?>
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <span style="font-size:2rem"><?php echo e($item->country->flag_emoji??'🏳️'); ?></span>
            <div class="fw-bold mt-1"><?php echo e($item->country_name); ?></div>
            <?php if($item->country): ?><div class="text-muted small"><?php echo e($item->country->region); ?> · <?php echo e($item->country->capital); ?></div><?php endif; ?>
          </div>
          <?php if($rs): ?>
          <div class="text-end">
            <div style="font-size:1.6rem;font-weight:700;color:<?php echo e($rs['marker_color']??'#666'); ?>"><?php echo e(number_format($rs['total_score'],1)); ?></div>
            <span class="badge bg-<?php echo e($rs['risk_badge_class']??'secondary'); ?>" style="font-size:.72rem"><?php echo e($rs['risk_label']??''); ?></span>
          </div>
          <?php endif; ?>
        </div>
        <?php if($rs): ?>
        <div class="mb-2">
          <?php $__currentLoopData = [['n'=>'Weather','s'=>$rs['weather_score'],'c'=>'info'],['n'=>'Inflation','s'=>$rs['inflation_score'],'c'=>'warning'],['n'=>'Currency','s'=>$rs['currency_score'],'c'=>'secondary'],['n'=>'News','s'=>$rs['news_sentiment_score'],'c'=>'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="d-flex align-items-center mb-1">
            <div class="text-muted" style="width:62px;font-size:.7rem"><?php echo e($comp['n']); ?></div>
            <div class="progress flex-fill" style="height:5px;border-radius:3px"><div class="progress-bar bg-<?php echo e($comp['c']); ?>" style="width:<?php echo e($comp['s']); ?>%"></div></div>
            <div class="ms-2 text-muted" style="width:26px;font-size:.7rem;text-align:right"><?php echo e(round($comp['s'])); ?></div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
        <?php if($item->notes): ?><div class="mt-2 p-2 bg-light rounded small text-muted">📝 <?php echo e($item->notes); ?></div><?php endif; ?>
      </div>
      <div class="card-footer bg-transparent d-flex gap-2">
        <a href="<?php echo e(route('countries.show',$item->country_code)); ?>" class="btn btn-outline-primary btn-sm flex-fill"><i class="bi bi-eye me-1"></i>Detail</a>
        <a href="<?php echo e(route('visualization.show',$item->country_code)); ?>" class="btn btn-outline-secondary btn-sm flex-fill"><i class="bi bi-graph-up me-1"></i>Chart</a>
        <form method="POST" action="<?php echo e(route('watchlist.remove',$item->id)); ?>" onsubmit="return confirm('Hapus?')">
          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
          <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title"><i class="bi bi-star me-2 text-warning"></i>Tambah ke Watchlist</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <select id="wlSel" class="form-select">
        <option value="">-- Pilih Negara --</option>
        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c->code); ?>"><?php echo e($c->flag_emoji); ?> <?php echo e($c->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button type="button" class="btn btn-warning fw-semibold" onclick="addToWl()">
        <i class="bi bi-star-fill me-2"></i>Tambahkan
      </button>
    </div>
  </div></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function addToWl(){
  const code=document.getElementById('wlSel').value;
  if(!code){alert('Pilih negara!');return;}
  ajaxPost('<?php echo e(route('watchlist.add')); ?>',{country_code:code},d=>{
    if(d.success){bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();location.reload();}
    else alert(d.message||'Gagal.');
  });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\watchlist\index.blade.php ENDPATH**/ ?>