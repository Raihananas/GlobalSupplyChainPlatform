<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — Supply Chain Risk Platform</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- Leaflet.js CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
:root {
  --sidebar-width: 260px;
  --topbar-height: 60px;
  --primary: #3b82f6;
  --dark: #0f172a;
  --accent-blue: #3b82f6;
  --accent-green: #10b981;
  --accent-orange: #f59e0b;
  --accent-red: #ef4444;
}

html, body {
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: radial-gradient(circle at top right, #111827, #090d16) no-repeat fixed;
  background-size: cover;
  color: #f8fafc !important;
  font-size: 14px !important;
}

/* Glassmorphism Card styling */
.card {
  background: rgba(17, 24, 39, 0.65) !important;
  backdrop-filter: blur(12px) !important;
  -webkit-backdrop-filter: blur(12px) !important;
  border: 1px solid rgba(255, 255, 255, 0.08) !important;
  border-radius: 12px !important;
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
  color: #f8fafc !important;
}
.card-header {
  background: rgba(255, 255, 255, 0.02) !important;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
  color: #f1f5f9 !important;
  font-weight: 600;
  padding: 14px 20px !important;
}
.card-body {
  padding: 20px !important;
}

/* Sidebar Styling */
.sidebar {
  position: fixed;
  top: 12px;
  left: 12px;
  height: calc(100vh - 24px);
  width: var(--sidebar-width);
  background: rgba(15, 23, 42, 0.75) !important;
  backdrop-filter: blur(16px) !important;
  -webkit-backdrop-filter: blur(16px) !important;
  border: 1px solid rgba(255, 255, 255, 0.06) !important;
  border-radius: 16px;
  color: #f8fafc;
  z-index: 1030;
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow-y: auto;
}
.sidebar-brand {
  padding: 22px 20px;
  font-size: 1rem;
  font-weight: 700;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  display: flex;
  align-items: center;
  gap: 10px;
}
.sidebar .nav-link {
  color: #94a3b8;
  padding: 12px 20px;
  border-radius: 8px;
  margin: 2px 10px;
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 0.92rem;
  transition: all 0.25s ease;
  white-space: nowrap;
}
.sidebar .nav-link:hover, .sidebar .nav-link.active {
  color: #ffffff !important;
  background: rgba(59, 130, 246, 0.2) !important;
  box-shadow: inset 0 0 10px rgba(59, 130, 246, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
}
.sidebar .nav-link i {
  font-size: 1.1rem;
  width: 20px;
  text-align: center;
}
.sidebar .nav-section {
  padding: 16px 20px 6px;
  font-size: .7rem;
  text-transform: uppercase;
  letter-spacing: .12em;
  color: #64748b;
  font-weight: 700;
  white-space: nowrap;
}

/* Main Content area */
.main-content {
  margin-left: calc(var(--sidebar-width) + 24px);
  min-height: 100vh;
  transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
}
.topbar {
  position: sticky;
  top: 12px;
  margin: 12px 12px 0 0;
  z-index: 1020;
  background: rgba(15, 23, 42, 0.5) !important;
  backdrop-filter: blur(12px) !important;
  -webkit-backdrop-filter: blur(12px) !important;
  border: 1px solid rgba(255, 255, 255, 0.08) !important;
  border-radius: 12px;
  height: var(--topbar-height);
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15) !important;
}
.page-content {
  padding: 16px 12px 16px 0;
}

/* Footer alignment */
footer {
  margin: auto 12px 12px 0 !important;
  background: rgba(15, 23, 42, 0.5) !important;
  backdrop-filter: blur(12px) !important;
  border: 1px solid rgba(255, 255, 255, 0.08) !important;
  border-radius: 12px;
}

/* Form inputs styling */
.form-control, .form-select, textarea {
  background-color: rgba(30, 41, 59, 0.45) !important;
  border: 1px solid rgba(255, 255, 255, 0.12) !important;
  color: #f8fafc !important;
  border-radius: 8px !important;
  transition: all 0.2s ease !important;
}
.form-control:focus, .form-select:focus, textarea:focus {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
  outline: none !important;
}
.form-label {
  color: #cbd5e1 !important;
}

/* Tables styling */
.table {
  --bs-table-bg: transparent !important;
  color: #f8fafc !important;
  vertical-align: middle;
}
.table th {
  background: rgba(255, 255, 255, 0.03) !important;
  color: #94a3b8 !important;
  border-bottom: 1.5px solid rgba(255, 255, 255, 0.1) !important;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.78rem;
  letter-spacing: 0.05em;
}
.table td {
  border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
  color: #e2e8f0 !important;
}
.table-hover tbody tr:hover td {
  background-color: rgba(255, 255, 255, 0.03) !important;
  color: #ffffff !important;
}

/* Risk Badges (Neon Glow theme) */
.risk-low {
  background: rgba(16, 185, 129, 0.15) !important;
  color: #34d399 !important;
  border: 1px solid rgba(16, 185, 129, 0.25);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: .78rem;
  font-weight: 600;
}
.risk-medium {
  background: rgba(245, 158, 11, 0.15) !important;
  color: #fbbf24 !important;
  border: 1px solid rgba(245, 158, 11, 0.25);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: .78rem;
  font-weight: 600;
}
.risk-high {
  background: rgba(239, 68, 68, 0.15) !important;
  color: #f87171 !important;
  border: 1px solid rgba(239, 68, 68, 0.25);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: .78rem;
  font-weight: 600;
}
.risk-critical {
  background: rgba(255, 255, 255, 0.1) !important;
  color: #ffffff !important;
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: .78rem;
  font-weight: 600;
}

/* Breadcrumbs */
.breadcrumb-item a {
  color: #94a3b8 !important;
  text-decoration: none;
}
.breadcrumb-item.active {
  color: #cbd5e1 !important;
}

/* Stat card */
.stat-card {
  border-radius: 12px;
  padding: 20px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}
.stat-card .stat-value {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1;
}
.stat-card .stat-label {
  font-size: .85rem;
  opacity: .85;
  margin-top: 6px;
}
.stat-card .stat-icon {
  font-size: 2.8rem;
  opacity: .2;
  position: absolute;
  right: 15px;
  bottom: 10px;
}

/* Loading Spinner */
.spinner-overlay {
  position: fixed;
  inset: 0;
  background: rgba(11, 15, 25, 0.8);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  display: none;
}

/* List group adjustments */
.list-group-item {
  background-color: transparent !important;
  border-color: rgba(255, 255, 255, 0.08) !important;
  color: #e2e8f0 !important;
}

/* Desktop Sidebar Collapse Styles */
@media(min-width:768px){
  .sidebar.collapsed-state {
    width: 65px !important;
  }
  .sidebar.collapsed-state + .main-content {
    margin-left: 90px !important;
  }
  .sidebar.collapsed-state .menu-text,
  .sidebar.collapsed-state .nav-section,
  .sidebar.collapsed-state .sidebar-brand div,
  .sidebar.collapsed-state .mt-auto div,
  .sidebar.collapsed-state .mt-auto form {
    display: none !important;
  }
  .sidebar.collapsed-state .sidebar-brand {
    justify-content: center !important;
    padding: 18px 0 !important;
  }
  .sidebar.collapsed-state .nav-link {
    justify-content: center !important;
    padding: 12px 0 !important;
  }
  .sidebar.collapsed-state .mt-auto {
    padding: 20px 0 !important;
    text-align: center !important;
  }
  .sidebar.collapsed-state .mt-auto img {
    margin: 0 auto !important;
  }
}

/* Responsive viewports */
@media(max-width:768px){
  .sidebar {
    top: 0;
    left: 0;
    height: 100vh;
    border-radius: 0;
    transform: translateX(-100%);
    transition: transform .3s ease !important;
  }
  .sidebar.show {
    transform: translateX(0);
  }
  .main-content {
    margin-left: 12px !important;
  }
  .topbar {
    margin-right: 12px !important;
  }
  footer {
    margin-right: 12px !important;
  }
}

/* Helper class overrides to ensure dark-mode visibility */
.text-muted, .text-secondary {
  color: #94a3b8 !important;
}
.text-dark {
  color: #f8fafc !important;
}
.bg-light {
  background-color: rgba(255, 255, 255, 0.05) !important;
}
.bg-white {
  background-color: rgba(17, 24, 39, 0.65) !important;
}
}
</style>
@stack('styles')
</head>
<body>

<div class="spinner-overlay" id="globalSpinner">
  <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
</div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
  <div class="sidebar-brand align-items-center">
    @if(file_exists(public_path('uploads/site/logo.png')))
        <img src="{{ asset('uploads/site/logo.png') }}?v={{ time() }}" alt="Logo" style="max-height: 36px; max-width: 36px; object-fit: contain; margin-right: 8px;">
    @else
        <i class="bi bi-globe2 text-primary fs-4"></i>
    @endif
    <div><div class="text-white" style="font-size:.85rem;font-weight:700">Supply Chain</div><span style="font-size:.7rem;color:#6c9fff">Risk Platform</span></div>
  </div>

  <div class="sidebar-section mt-2">
    <div class="nav-section">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i> <span class="menu-text">Dashboard</span>
    </a>
  </div>

  <div class="nav-section mt-2">Analytics</div>
  <a href="{{ route('countries.index') }}" class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}">
    <i class="bi bi-globe"></i> <span class="menu-text">Country Dashboard</span>
  </a>
  <a href="{{ route('weather.index') }}" class="nav-link {{ request()->routeIs('weather.*') ? 'active' : '' }}">
    <i class="bi bi-cloud-sun"></i> <span class="menu-text">Weather Monitoring</span>
  </a>
  <a href="{{ route('currency.index') }}" class="nav-link {{ request()->routeIs('currency.*') ? 'active' : '' }}">
    <i class="bi bi-currency-exchange"></i> <span class="menu-text">Currency Impact</span>
  </a>
  <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">
    <i class="bi bi-newspaper"></i> <span class="menu-text">News Intelligence</span>
  </a>
  <a href="{{ route('ports.index') }}" class="nav-link {{ request()->routeIs('ports.*') ? 'active' : '' }}">
    <i class="bi bi-anchor"></i> <span class="menu-text">Port Locations</span>
  </a>
  <a href="{{ route('comparison.index') }}" class="nav-link {{ request()->routeIs('comparison.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-steps"></i> <span class="menu-text">Country Comparison</span>
  </a>
  <a href="{{ route('visualization.index') }}" class="nav-link {{ request()->routeIs('visualization.*') ? 'active' : '' }}">
    <i class="bi bi-graph-up"></i> <span class="menu-text">Data Visualization</span>
  </a>

  <div class="nav-section mt-2">Personal</div>
  <a href="{{ route('watchlist.index') }}" class="nav-link {{ request()->routeIs('watchlist.*') ? 'active' : '' }}">
    <i class="bi bi-star"></i> <span class="menu-text">Watchlist</span>
  </a>

  @if(auth()->user()?->isAdmin())
  <div class="nav-section mt-2">Admin</div>
  <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
    <i class="bi bi-shield-check"></i> <span class="menu-text">Admin Panel</span>
  </a>
  @endif

  <div class="mt-auto" style="padding:20px;border-top:1px solid rgba(255,255,255,.1);margin-top:20px">
    <div class="d-flex align-items-center gap-2">
      <img src="{{ auth()->user()?->avatar_url }}" width="34" height="34" class="rounded-circle" alt="avatar">
      <div>
        <div style="font-size:.82rem;font-weight:600;color:#fff">{{ auth()->user()?->name }}</div>
        <div style="font-size:.7rem;color:#888">{{ ucfirst(auth()->user()?->role) }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
      @csrf
      <button class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>
  </div>
</nav>

<!-- Main -->
<div class="main-content">
  <!-- Topbar -->
  <div class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="bi bi-list"></i>
      </button>
      <button class="btn btn-sm btn-outline-secondary d-none d-md-inline-block border-opacity-25" onclick="toggleSidebar()" style="border-radius: 6px;">
        <i class="bi bi-indent"></i>
      </button>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
          @yield('breadcrumb')
        </ol>
      </nav>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span class="badge bg-primary-subtle text-primary small">
        <i class="bi bi-circle-fill text-success" style="font-size:.5rem"></i> Live
      </span>
      <span class="text-muted small d-none d-md-inline" id="currentTime"></span>
    </div>
  </div>

  <!-- Alerts -->
  <div style="padding:0 24px">
    @foreach(['success'=>'success','error'=>'danger','warning'=>'warning','info'=>'info'] as $type => $class)
      @if(session($type))
        <div class="alert alert-{{ $class }} alert-dismissible fade show mt-3 mb-0" role="alert">
          {{ session($type) }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    @endforeach
  </div>

  <!-- Page content -->
  <div class="page-content">
    @yield('content')
  </div>

  <!-- Copyright Footer -->
  <footer class="text-center py-3 border-top border-secondary border-opacity-10 mt-auto bg-white" id="dashboard-footer">
    <span class="text-muted small" id="dashboard-copyright-text">&copy; {{ date('Y') }} <strong>Muslim Gunawan</strong>. All rights reserved.</span>
  </footer>
</div><!-- /main-content -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<!-- Leaflet.js -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Real-time clock
function updateClock(){
  const now = new Date();
  document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}) + ' WIB';
}
setInterval(updateClock, 1000); updateClock();

// CSRF for AJAX
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

// Global spinner
function showSpinner(){ document.getElementById('globalSpinner').style.display='flex'; }
function hideSpinner(){ document.getElementById('globalSpinner').style.display='none'; }

// AJAX helper
function ajaxPost(url, data, onSuccess, onError){
  showSpinner();
  fetch(url, {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
    body: JSON.stringify(data)
  })
  .then(r=>r.json())
  .then(d=>{ hideSpinner(); onSuccess(d); })
  .catch(e=>{ hideSpinner(); if(onError) onError(e); });
}

// Risk color helper
function riskColor(level){
  return {'low':'#198754','medium':'#ffc107','high':'#dc3545','critical':'#212529'}[level]||'#6c757d';
}
function riskBg(level){
  return {'low':'#d1f5e0','medium':'#fff3cd','high':'#fde8e8','critical':'#2d2d2d'}[level]||'#e9ecef';
}

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) {
    sidebar.classList.toggle('collapsed-state');
    // Invalidate map sizes if maps are loaded on this page
    if (typeof worldMap !== 'undefined') setTimeout(() => { worldMap.invalidateSize(); }, 350);
    if (typeof wMap !== 'undefined') setTimeout(() => { wMap.invalidateSize(); }, 350);
    if (typeof pMap !== 'undefined') setTimeout(() => { pMap.invalidateSize(); }, 350);
  }
}
</script>
<script>
(function() {
    const ownerName = "Muslim Gunawan";
    const footerText = document.getElementById("dashboard-copyright-text");
    if (footerText) {
        const observer = new MutationObserver(() => {
            const expectedText = `© ${new Date().getFullYear()} Muslim Gunawan. All rights reserved.`;
            if (footerText.innerText !== expectedText || footerText.innerHTML.indexOf("Muslim Gunawan") === -1) {
                footerText.innerHTML = `&copy; ${new Date().getFullYear()} <strong>Muslim Gunawan</strong>. All rights reserved.`;
            }
        });
        observer.observe(footerText, { childList: true, characterData: true, subtree: true });
    }
})();
</script>
@stack('scripts')
</body>
</html>
