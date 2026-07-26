<?php $__env->startSection('title','Global Weather Monitoring'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Weather Monitoring</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold"><i class="bi bi-cloud-sun me-2 text-info"></i>Global Weather Monitoring</h4>
  <select class="form-select form-select-sm" style="width:200px" onchange="if(this.value)window.location='/weather/'+this.value">
    <option value="">Pilih Negara...</option>
    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c->code); ?>"><?php echo e($c->flag_emoji); ?> <?php echo e($c->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
</div>
<div class="card mb-3"><div class="card-body py-2 d-flex flex-wrap gap-3 align-items-center">
  <small class="text-muted fw-medium">Legend:</small>
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#198754;margin-right:4px;vertical-align:middle"></span><small>Low Risk (0-30)</small></span>
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#ffc107;margin-right:4px;vertical-align:middle"></span><small>Medium Risk (31-60)</small></span>
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#dc3545;margin-right:4px;vertical-align:middle"></span><small>High Risk (61+)</small></span>
  <span>⛈️<small>Badai</small></span><span>🌧️<small>Hujan Lebat</small></span><span>💨<small>Angin Kencang</small></span>
</div></div>
<div class="card mb-4"><div class="card-header fw-semibold"><i class="bi bi-map me-2 text-info"></i>Peta Cuaca Dunia <small class="text-muted fw-normal">— Klik marker untuk detail</small></div>
<div class="card-body p-0"><div id="weatherMap" style="height:450px;border-radius:0 0 12px 12px"></div></div></div>
<div class="row g-3">
  <?php $__empty_1 = true; $__currentLoopData = $mapMarkers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100" style="cursor:pointer" onclick="window.location='/weather/<?php echo e($w['country_code']); ?>'">
      <div class="card-body text-center py-3">
        <div style="font-size:1.8rem"><?php $wc=$w['weathercode']??0;echo match(true){in_array($wc,[0,1])=>'☀️',in_array($wc,[2,3])=>'⛅',in_array($wc,[45,48])=>'🌫️',in_array($wc,[51,61,63])=>'🌦️',in_array($wc,[65,80,81,82])=>'🌧️',in_array($wc,[95,96,99])=>'⛈️',default=>'🌡️'};?></div>
        <div class="fw-semibold small mt-1"><?php echo e($w['country_name']); ?></div>
        <?php if($w['temperature']!==null): ?><div class="fw-bold" style="font-size:1.3rem"><?php echo e(number_format($w['temperature'],1)); ?>°C</div><?php endif; ?>
        <div class="text-muted" style="font-size:.72rem"><?php echo e($w['description']??''); ?></div>
        <?php $rs=$w['risk_score']??0;$rl=$rs>60?'danger':($rs>30?'warning':'success');?>
        <span class="badge bg-<?php echo e($rl); ?> mt-1" style="font-size:.68rem">Risk: <?php echo e(round($rs)); ?></span>
        <div class="d-flex justify-content-center gap-1 mt-1">
          <?php if($w['is_storm']??false): ?><span>⛈️</span><?php endif; ?>
          <?php if($w['is_heavy_rain']??false): ?><span>🌧️</span><?php endif; ?>
          <?php if($w['is_strong_wind']??false): ?><span>💨</span><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <div class="col-12 text-center py-5 text-muted">
    <i class="bi bi-cloud-slash display-4 d-block mb-3 opacity-25"></i>Belum ada data cuaca. Pilih negara dari dropdown.
  </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const corner1 = L.latLng(-90, -180);
const corner2 = L.latLng(90, 180);
const bounds = L.latLngBounds(corner1, corner2);

const wMap=L.map('weatherMap',{
  zoom:2,
  center:[20,0],
  maxBounds: bounds,
  maxBoundsViscosity: 1.0
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
  attribution:'© OpenStreetMap',
  maxZoom:18,
  noWrap: true,
  bounds: bounds
}).addTo(wMap);
const mks=<?php echo json_encode($mapMarkers??[], 15, 512) ?>;
mks.forEach(w=>{
  if(!w.lat||!w.lng)return;
  const rs=w.risk_score||0,col=rs>60?'#dc3545':(rs>30?'#ffc107':'#198754');
  L.circleMarker([w.lat,w.lng],{radius:10,color:'#fff',weight:2,fillColor:col,fillOpacity:.85}).addTo(wMap)
   .bindPopup(`<b>${w.country_name}</b><br><span style="font-size:1.2rem">${w.temperature!==null?w.temperature.toFixed(1)+'°C':'--'}</span><br><small>${w.description||''}</small><br>🌧️${w.precipitation||0}mm 💨${w.windspeed||0}km/h<br><span style="background:${col};color:#fff;padding:2px 8px;border-radius:10px;font-size:.75rem">Risk:${rs.toFixed(0)}</span>`)
   .on('click',()=>{window.location='/weather/'+w.country_code;});
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/weather/index.blade.php ENDPATH**/ ?>