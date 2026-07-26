<?php $__env->startSection('title', $country->name); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('countries.index')); ?>">Countries</a></li>
<li class="breadcrumb-item active"><?php echo e($country->name); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="card mb-4" style="background:linear-gradient(135deg,#1a1d23,#0d3880);color:#fff;border:none">
  <div class="card-body p-4">
    <div class="row align-items-center g-3">
      <div class="col-auto"><span style="font-size:4rem;line-height:1"><?php echo e($country->flag_emoji??'🏳️'); ?></span></div>
      <div class="col">
        <h2 class="mb-1 fw-bold text-white"><?php echo e($country->name); ?></h2>
        <div class="opacity-75 small"><?php echo e($country->capital); ?> · <?php echo e($country->region); ?> · <?php echo e($country->subregion); ?></div>
        <?php if($country->currency_code): ?>
        <div class="mt-1 small opacity-75">
          <i class="bi bi-currency-exchange me-1"></i><?php echo e($country->currency_code); ?> — <?php echo e($country->currency_name); ?>

          <?php if($currencyRate): ?> &nbsp;|&nbsp; 1 USD = <?php echo e(number_format($currencyRate,2)); ?> <?php echo e($country->currency_code); ?> <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-auto text-end">
        <?php if(isset($riskScore['total_score'])): ?>
        <div class="mb-1 opacity-75 small">Risk Score</div>
        <div style="font-size:2.8rem;font-weight:700;color:<?php echo e($riskScore['marker_color']??'#fff'); ?>"><?php echo e(number_format($riskScore['total_score'],1)); ?></div>
        <span class="badge fs-6" style="background:<?php echo e($riskScore['marker_color']??'#666'); ?>"><?php echo e($riskScore['risk_label']??''); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  
  <div class="col-lg-8">
    
    <?php if(isset($riskScore['total_score'])): ?>
    <div class="card mb-4">
      <div class="card-header fw-semibold">
        <i class="bi bi-shield-exclamation me-2 text-primary"></i>Risk Score Breakdown
        <small class="text-muted fw-normal ms-2">Weather(<?php echo e($riskScore['weather_weight']); ?>%) + Inflation(<?php echo e($riskScore['inflation_weight']); ?>%) + Currency(<?php echo e($riskScore['currency_weight']); ?>%) + News(<?php echo e($riskScore['news_weight']); ?>%)</small>
      </div>
      <div class="card-body">
        <div class="row g-3 mb-3">
          <?php $__currentLoopData = [['label'=>'Weather','score'=>$riskScore['weather_score'],'icon'=>'bi-cloud-sun','color'=>'info'],['label'=>'Inflation','score'=>$riskScore['inflation_score'],'icon'=>'bi-graph-down','color'=>'warning'],['label'=>'Currency','score'=>$riskScore['currency_score'],'icon'=>'bi-currency-exchange','color'=>'secondary'],['label'=>'News','score'=>$riskScore['news_sentiment_score'],'icon'=>'bi-newspaper','color'=>'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-6 col-md-3 text-center">
            <div class="p-3 rounded" style="background:#f8f9fa">
              <i class="bi <?php echo e($c['icon']); ?> fs-3 text-<?php echo e($c['color']); ?>"></i>
              <div class="fw-bold mt-1" style="font-size:1.6rem"><?php echo e(number_format($c['score'],1)); ?></div>
              <div class="text-muted small"><?php echo e($c['label']); ?></div>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="d-flex justify-content-between small mb-1">
          <span class="text-muted">Total</span>
          <span class="fw-semibold"><?php echo e(number_format($riskScore['total_score'],1)); ?>/100</span>
        </div>
        <div class="progress" style="height:16px;border-radius:8px">
          <div class="progress-bar bg-<?php echo e($riskScore['risk_badge_class']??'secondary'); ?>" style="width:<?php echo e($riskScore['total_score']); ?>%;border-radius:8px;transition:width .8s"></div>
        </div>
        <div class="d-flex justify-content-between mt-1" style="font-size:.7rem;color:#aaa">
          <span>0 Low</span><span>30</span><span>60</span><span>80</span><span>100 Critical</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    
    <div class="card mb-4">
      <div class="card-header fw-semibold"><i class="bi bi-bar-chart me-2 text-primary"></i>Economic Indicators <small class="text-muted fw-normal">(World Bank API)</small></div>
      <div class="card-body">
        <?php if($economic && array_filter($economic)): ?>
        <div class="row g-3">
          <?php if(!empty($economic['gdp'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">GDP</div><div class="fw-bold fs-5"><?php $g=$economic['gdp'];echo $g>=1e12?'$'.round($g/1e12,2).'T':($g>=1e9?'$'.round($g/1e9,2).'B':'$'.number_format($g));?></div></div></div><?php endif; ?>
          <?php if(!empty($economic['inflation'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">Inflasi</div><div class="fw-bold fs-5 <?php echo e(abs($economic['inflation'])>10?'text-danger':''); ?>"><?php echo e(number_format($economic['inflation'],2)); ?>%</div></div></div><?php endif; ?>
          <?php if(!empty($economic['population'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">Populasi</div><div class="fw-bold fs-5"><?php $p=$economic['population'];echo $p>=1e9?round($p/1e9,2).'B':($p>=1e6?round($p/1e6,1).'M':number_format($p));?></div></div></div><?php endif; ?>
          <?php if(!empty($economic['exports'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">Ekspor</div><div class="fw-bold fs-5 text-success"><?php $e=$economic['exports'];echo $e>=1e12?'$'.round($e/1e12,2).'T':($e>=1e9?'$'.round($e/1e9,2).'B':'$'.number_format($e));?></div></div></div><?php endif; ?>
          <?php if(!empty($economic['imports'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">Impor</div><div class="fw-bold fs-5 text-danger"><?php $i=$economic['imports'];echo $i>=1e12?'$'.round($i/1e12,2).'T':($i>=1e9?'$'.round($i/1e9,2).'B':'$'.number_format($i));?></div></div></div><?php endif; ?>
          <?php if(!empty($economic['unemployment'])): ?><div class="col-6 col-md-4"><div class="p-3 rounded bg-light text-center"><div class="text-muted small">Pengangguran</div><div class="fw-bold fs-5"><?php echo e(number_format($economic['unemployment'],2)); ?>%</div></div></div><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-3"><i class="bi bi-cloud-slash d-block fs-3 mb-2 opacity-25"></i>Data ekonomi tidak tersedia (World Bank API).</div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="row g-3">
      <div class="col-md-6"><div class="card h-100"><div class="card-header small fw-semibold"><i class="bi bi-bar-chart text-success me-1"></i>GDP Trend</div><div class="card-body"><canvas id="gdpChart" height="200"></canvas></div></div></div>
      <div class="col-md-6"><div class="card h-100"><div class="card-header small fw-semibold"><i class="bi bi-graph-down-arrow text-danger me-1"></i>Inflation Trend</div><div class="card-body"><canvas id="inflChart" height="200"></canvas></div></div></div>
    </div>
  </div>

  
  <div class="col-lg-4">
    
    <?php if($weather): ?>
    <div class="card mb-4" style="background:linear-gradient(135deg,#1a3a5c,#1a6ba8);color:#fff;border:none">
      <div class="card-body text-center p-3">
        <div class="small opacity-75 mb-2">Cuaca Saat Ini — Open-Meteo API</div>
        <div style="font-size:3rem;line-height:1"><?php $wc=$weather['weathercode']??0;echo match(true){in_array($wc,[0,1])=>'☀️',in_array($wc,[2,3])=>'⛅',in_array($wc,[45,48])=>'🌫️',in_array($wc,[51,61,63])=>'🌦️',in_array($wc,[65,80,81,82])=>'🌧️',in_array($wc,[95,96,99])=>'⛈️',default=>'🌡️'};?></div>
        <div style="font-size:2.5rem;font-weight:700"><?php echo e($weather['temperature_2m']??'--'); ?>°C</div>
        <div class="opacity-80 small mb-2"><?php echo e($weather['weather_description']??''); ?></div>
        <div class="row g-1">
          <div class="col-4"><div style="font-size:.62rem;opacity:.7">Hujan</div><div class="small fw-semibold"><?php echo e($weather['precipitation']??0); ?>mm</div></div>
          <div class="col-4"><div style="font-size:.62rem;opacity:.7">Angin</div><div class="small fw-semibold"><?php echo e($weather['windspeed_10m']??0); ?>km/h</div></div>
          <div class="col-4"><div style="font-size:.62rem;opacity:.7">Lembab</div><div class="small fw-semibold"><?php echo e($weather['humidity']??'--'); ?>%</div></div>
        </div>
        <?php if($weather['is_storm']||$weather['is_heavy_rain']||$weather['is_strong_wind']): ?>
        <div class="mt-2 d-flex flex-wrap gap-1 justify-content-center">
          <?php if($weather['is_storm']): ?><span class="badge bg-danger">⛈️ Storm</span><?php endif; ?>
          <?php if($weather['is_heavy_rain']): ?><span class="badge bg-primary">🌧️ Heavy Rain</span><?php endif; ?>
          <?php if($weather['is_strong_wind']): ?><span class="badge bg-warning text-dark">💨 Strong Wind</span><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="mt-2 p-2 rounded" style="background:rgba(255,255,255,.15)">
          <small>Weather Risk: <b><?php echo e(number_format($weather['weather_risk_score']??0,1)); ?>/100</b></small>
        </div>
      </div>
    </div>
    <?php endif; ?>

    
    <div class="card mb-4">
      <div class="card-body d-grid gap-2">
        <button id="wlBtn" class="btn btn-<?php echo e($isWatchlisted?'warning':'outline-warning'); ?> btn-sm"
          onclick="<?php echo e($isWatchlisted?'removeWl':'addWl'); ?>('<?php echo e($country->code); ?>')">
          <i class="bi bi-star<?php echo e($isWatchlisted?'-fill':''); ?> me-2"></i>
          <?php echo e($isWatchlisted?'Hapus dari Watchlist':'Tambah ke Watchlist'); ?>

        </button>
        <a href="<?php echo e(route('comparison.index')); ?>?a=<?php echo e($country->code); ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-bar-chart-steps me-2"></i>Bandingkan Negara</a>
        <a href="<?php echo e(route('visualization.show',$country->code)); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-graph-up me-2"></i>Data Visualization</a>
        <a href="<?php echo e(route('weather.show',$country->code)); ?>" class="btn btn-outline-info btn-sm"><i class="bi bi-cloud-sun me-2"></i>Weather Detail</a>
        <a href="<?php echo e(route('ports.index')); ?>?country=<?php echo e($country->code); ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-anchor me-2"></i>Pelabuhan</a>
        <a href="<?php echo e(route('currency.show',$country->code)); ?>" class="btn btn-outline-warning btn-sm"><i class="bi bi-currency-exchange me-2"></i>Currency Detail</a>
      </div>
    </div>

    
    <div class="card">
      <div class="card-header small fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Info Negara</div>
      <div class="card-body p-0">
        <table class="table table-sm table-borderless mb-0 small">
          <tr><td class="text-muted ps-3" style="width:45%">Kode ISO</td><td class="fw-medium"><?php echo e($country->code); ?> / <?php echo e($country->code3); ?></td></tr>
          <tr><td class="text-muted ps-3">Region</td><td class="fw-medium"><?php echo e($country->region); ?></td></tr>
          <tr><td class="text-muted ps-3">Ibu Kota</td><td class="fw-medium"><?php echo e($country->capital); ?></td></tr>
          <tr><td class="text-muted ps-3">Mata Uang</td><td class="fw-medium"><?php echo e($country->currency_code); ?></td></tr>
          <?php if($country->population): ?><tr><td class="text-muted ps-3">Populasi</td><td class="fw-medium"><?php $p=$country->population;echo $p>=1e9?round($p/1e9,2).'B':($p>=1e6?round($p/1e6,1).'M':number_format($p));?></td></tr><?php endif; ?>
          <?php if($country->languages): ?><tr><td class="text-muted ps-3">Bahasa</td><td class="fw-medium"><?php echo e(implode(', ',array_slice((array)$country->languages,0,3))); ?></td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const gdpData=<?php echo json_encode($gdpTrend??[], 15, 512) ?>,inflData=<?php echo json_encode($inflationTrend??[], 15, 512) ?>;
if(gdpData.length){new Chart(document.getElementById('gdpChart').getContext('2d'),{type:'bar',data:{labels:gdpData.map(d=>d.year),datasets:[{data:gdpData.map(d=>d.value),backgroundColor:'rgba(25,135,84,.75)',borderRadius:4}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>v>=1e12?'$'+(v/1e12).toFixed(1)+'T':v>=1e9?'$'+(v/1e9).toFixed(1)+'B':'$'+v}}}}});}
else document.getElementById('gdpChart').parentElement.innerHTML='<div class="text-center text-muted py-4 small">Data GDP tidak tersedia</div>';
if(inflData.length){new Chart(document.getElementById('inflChart').getContext('2d'),{type:'line',data:{labels:inflData.map(d=>d.year),datasets:[{data:inflData.map(d=>d.value),borderColor:'#dc3545',backgroundColor:'rgba(220,53,69,.1)',tension:.4,fill:true,pointRadius:5}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>v+'%'}}}}});}
else document.getElementById('inflChart').parentElement.innerHTML='<div class="text-center text-muted py-4 small">Data inflasi tidak tersedia</div>';

function addWl(code){ajaxPost('<?php echo e(route('watchlist.add')); ?>',{country_code:code},d=>{if(d.success){document.getElementById('wlBtn').className='btn btn-warning btn-sm';document.getElementById('wlBtn').innerHTML='<i class="bi bi-star-fill me-2"></i>Hapus dari Watchlist';document.getElementById('wlBtn').setAttribute('onclick','removeWl(\''+code+'\')');showT(d.message,'success');}else showT(d.message||'Gagal','danger');});}
function removeWl(){window.location='<?php echo e(route('watchlist.index')); ?>';}
function showT(msg,type){const e=document.createElement('div');e.className=`alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;e.style.zIndex=9999;e.textContent=msg;document.body.appendChild(e);setTimeout(()=>e.remove(),3000);}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views/countries/show.blade.php ENDPATH**/ ?>