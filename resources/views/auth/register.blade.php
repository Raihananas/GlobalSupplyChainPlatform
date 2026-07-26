<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Supply Chain Risk Platform</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:radial-gradient(circle at top right, #111827, #090d16) no-repeat fixed;min-height:100vh;display:flex;align-items:center;justify-content:center;color:#f8fafc;font-family:'Segoe UI',sans-serif}
.register-card{width:440px;max-width:95vw;border-radius:16px;overflow:hidden;box-shadow:0 8px 32px 0 rgba(0, 0, 0, 0.4);background:rgba(17,24,39,0.75) !important;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.08)}
.card-header-custom{background:rgba(255,255,255,0.02);border-bottom:1px solid rgba(255,255,255,0.08);padding:24px 32px;color:#fff}
.card-body-custom{padding:32px}
.form-control{background-color:rgba(30,41,59,0.45) !important;border:1px solid rgba(255,255,255,0.12) !important;color:#f8fafc !important;border-radius:8px !important}
.form-control:focus{border-color:#3b82f6 !important;box-shadow:0 0 0 3px rgba(59,130,246,0.3) !important}
.form-label {color:#cbd5e1 !important}
</style>
</head>
<body>
<div class="register-card">
  <div class="card-header-custom">
    <div class="d-flex align-items-center gap-3">
      @if(file_exists(public_path('uploads/site/logo.png')))
        <img src="{{ asset('uploads/site/logo.png') }}?v={{ time() }}" alt="Logo" style="max-height: 40px; max-width: 40px; object-fit: contain;">
      @else
        <i class="bi bi-globe2 fs-2 opacity-90"></i>
      @endif
      <div>
        <div class="fw-bold fs-5">Daftar Akun Baru</div>
        <div class="small opacity-75">Supply Chain Risk Platform</div>
      </div>
    </div>
  </div>
  <div class="card-body-custom">
    @if($errors->any())
    <div class="alert alert-danger py-2 bg-danger bg-opacity-15 border-danger border-opacity-25 text-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-medium small">Nama Lengkap</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
          placeholder="Nama Anda" value="{{ old('name') }}" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label fw-medium small">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
          placeholder="email@example.com" value="{{ old('email') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label fw-medium small">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-medium small">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
        <i class="bi bi-person-plus me-2"></i>Buat Akun
      </button>
    </form>
    <p class="text-center text-muted small mt-3 mb-0">
      Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-medium">Masuk di sini</a>
    </p>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
