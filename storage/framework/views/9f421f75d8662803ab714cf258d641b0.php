<?php $__env->startSection('title','Cuaca — '.$country->name); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('weather.index')); ?>">Weather</a></li>
<li class="breadcrumb-item active"><?php echo e($country->name); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card mb-4" style="background:linear-gradient(135deg,#1a3a5c,#1a6ba8);color:#fff;border:none">
      <div class="card-body text-center p-4">
        <div style="font-size:2.5rem"><?php echo e($country->flag_emoji??'🏳️'); ?></div>
        <h4 class="text-white mt-1 mb-3"><?php echo e($country->name); ?></h4>
        <?php if($weather): ?>
        <?php
        $wc=$weather['weathercode']??0;
        $icon=match(true){
          in_array($wc,[0,1])  =>'☀️',
          in_array($wc,[2,3])  =>'⛅',
          in_array($wc,[45,48])=>'🌫️',
          in_array($wc,[51,61,63])=>'🌦️',
          in_array($wc,[65,80,81,82])=>'🌧️',
          in_array($wc,[95,96,99])=>'⛈️',
          default=>'🌡️'
        };
        ?>
        <div style="font-size:4rem;line-height:1"><?php echo e($icon); ?></div>
        <div style="font-size:3rem;font-weight:700"><?php echo e($weather['temperature_2m']??'--'); ?>°C</div>
        <div class="opacity-80 mb-3"><?php echo e($weather['weather_description']??''); ?></div>
        <div class="row g-2">
          <div class="col-4"><div style="font-size:.65rem;opacity:.7">Hujan</div><div class="fw-semibold small"><?php echo e($weather['precipitation']??0); ?>mm</div></div>
          <div class="col-4"><div style="font-size:.65rem;opacity:.7">Angin</div><div class="fw-semibold small"><?php echo e($weather['windspeed_10m']??0); ?>km/h</div></div>
          <div class="col-4"><div style="font-size:.65rem;opacity:.7">Lembab</div><div class="fw-semibold small"><?php echo e($weather['humidity']??'--'); ?>%</div></div>
        </div>
        <?php if($weather['is_storm']||$weather['is_heavy_rain']||$weather['is_strong_wind']): ?>
        <div class="mt-2 d-flex flex-wrap gap-1 justify-content-center">
          <?php if($weather['is_storm']): ?><span class="badge bg-danger">⛈️ STORM</span><?php endif; ?>
          <?php if($weather['is_heavy_rain']): ?><span class="badge bg-primary">🌧️ HEAVY RAIN</span><?php endif; ?>
          <?php if($weather['is_strong_wind']): ?><span class="badge bg-warning text-dark">💨 STRONG WIND</span><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="mt-3 p-2 rounded" style="background:rgba(255,255,255,.15)">
          <div style="font-size:.75rem;opacity:.8">Weather Risk Score</div>
          <div style="font-size:1.8rem;font-weight:700"><?php echo e(number_format($weather['weather_risk_score']??0,1)); ?>/100</div>
          <?php $wr=$weather['weather_risk_score']??0; ?>
          <small><?php echo e($wr<=30?'✅ Low Risk':($wr<=60?'⚠️ Medium':($wr<=80?'🔴 High':'🚨 Critical'))); ?></small>
        </div>
        <?php else: ?>
        <div class="py-4 opacity-70">Data cuaca tidak tersedia</div>
        <?php endif; ?>
      </div>
    </div>
    <div class="card"><div class="card-body d-grid gap-2">
      <a href="<?php echo e(route('countries.show',$country->code)); ?>" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-globe me-2"></i>Country Dashboard
      </a>
      <a href="<?php echo e(route('weather.index')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Peta
      </a>
    </div></div>
  </div>

  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header fw-semibold"><i class="bi bi-geo-alt me-2 text-info"></i>Lokasi — <?php echo e($country->name); ?></div>
      <div class="card-body p-0"><div id="cMap" style="height:260px"></div></div>
    </div>

    <div class="card">
      <div class="card-header fw-semibold"><i class="bi bi-calendar-week me-2 text-info"></i>Prakiraan 7 Hari</div>
      <div class="card-body">
        <?php if($forecast && isset($forecast['daily'])): ?>
        <?php
        $fd  = $forecast['daily'];
        $wicons = [0=>'☀️',1=>'🌤️',2=>'⛅',3=>'☁️',45=>'🌫️',51=>'🌦️',61=>'🌦️',63=>'🌧️',65=>'🌧️',80=>'🌧️',82=>'⛈️',95=>'⛈️',99=>'⛈️'];
        ?>
        <div class="row g-2 mb-4">
          <?php $__currentLoopData = $fd['time']??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col">
            <div class="text-center p-2 rounded" style="background:#f8f9fa">
              <div class="text-muted" style="font-size:.65rem"><?php echo e(\Carbon\Carbon::parse($date)->format('D')); ?></div>
              <div style="font-size:1.3rem"><?php echo e($wicons[$fd['weathercode'][$i]??0]??'🌡️'); ?></div>
              <div class="fw-semibold small"><?php echo e(round($fd['temperature_2m_max'][$i]??0)); ?>°</div>
              <div class="text-muted" style="font-size:.72rem"><?php echo e(round($fd['temperature_2m_min'][$i]??0)); ?>°</div>
              <?php if(($fd['precipitation_sum'][$i]??0)>0): ?>
              <div style="font-size:.65rem;color:#0d6efd">💧<?php echo e(round($fd['precipitation_sum'][$i],1)); ?>mm</div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <canvas id="fChart" height="120"></canvas>
        <?php else: ?>
        <div class="text-center text-muted py-3">
          <i class="bi bi-cloud-slash d-block fs-3 mb-2 opacity-25"></i>Data prakiraan tidak tersedia
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const cm=L.map('cMap',{zoom:4,center:[<?php echo e($country->latitude??0); ?>,<?php echo e($country->longitude??0); ?>],zoomControl:false});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(cm);
L.marker([<?php echo e($country->latitude??0); ?>,<?php echo e($country->longitude??0); ?>]).addTo(cm).bindPopup('<b><?php echo e($country->name); ?></b>').openPopup();
<?php if($forecast && isset($forecast['daily'])): ?>
<?php $fd=$forecast['daily']; ?>
new Chart(document.getElementById('fChart').getContext('2d'),{
  type:'line',
  data:{
    labels:<?php echo json_encode(array_map(fn($x)=>\Carbon\Carbon::parse($x)->format('D'), $fd['time']??[]), 512) ?>,
    datasets:[
      {label:'Max°C',data:<?php echo json_encode($fd['temperature_2m_max']??[], 15, 512) ?>,borderColor:'#dc3545',tension:.4,fill:false,pointRadius:4},
      {label:'Min°C',data:<?php echo json_encode($fd['temperature_2m_min']??[], 15, 512) ?>,borderColor:'#0d6efd',tension:.4,fill:false,pointRadius:4}
    ]
  },
  options:{responsive:true,plugins:{legend:{position:'top',labels:{font:{size:11}}}},scales:{y:{ticks:{callback:v=>v+'°C'}}}}
});
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/weather/show.blade.php ENDPATH**/ ?>