<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Supply Chain Risk Platform</title>

<link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(time()); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:radial-gradient(circle at top right,#111827,#090d16) no-repeat fixed;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#f8fafc;
    font-family:'Segoe UI',sans-serif;
}

.login-card{
    width:420px;
    max-width:95vw;
    border-radius:16px;
    overflow:hidden;  
    box-shadow:0 8px 32px rgba(0,0,0,.4);
    background:rgba(17,24,39,.75);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,.08);
}

.login-header{
    background:rgba(255,255,255,.02);
    border-bottom:1px solid rgba(255,255,255,.08);
    padding:32px 32px 24px;
    color:#fff;
}

.login-body{
    padding:32px;
}

.form-control{
    background:rgba(30,41,59,.45)!important;
    border:1px solid rgba(255,255,255,.12)!important;
    color:#f8fafc!important;
    border-radius:8px!important;
}

.form-control:focus{
    border-color:#3b82f6!important;
    box-shadow:0 0 0 3px rgba(59,130,246,.3)!important;
}

.input-group-text{
    background:rgba(30,41,59,.5)!important;
    border:1px solid rgba(255,255,255,.12)!important;
    color:#94a3b8!important;
}

.form-label{
    color:#cbd5e1!important;
}

.form-check-label{
    color:#cbd5e1;
}
</style>

</head>

<body>

<div class="login-card">

    <div class="login-header">

        <div class="d-flex align-items-center gap-3 mb-3">

            <?php if(file_exists(public_path('uploads/site/logo.png'))): ?>
                <img src="<?php echo e(asset('uploads/site/logo.png')); ?>?v=<?php echo e(time()); ?>"
                     alt="Logo"
                     style="max-height:48px;max-width:48px;object-fit:contain;">
            <?php else: ?>
                <i class="bi bi-globe2" style="font-size:2.5rem;opacity:.9"></i>
            <?php endif; ?>

            <div>
                <div class="fw-bold fs-5">Supply Chain Risk</div>
                <div style="font-size:.85rem;opacity:.8">
                    Intelligence Platform
                </div>
            </div>

        </div>

        <p class="mb-0 small opacity-75">
            Monitor risiko rantai pasok global secara real-time
        </p>

    </div>

    <div class="login-body">

        <h3 class="mb-4 fw-bold text-white">
            Masuk ke Akun
        </h3>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger py-2">
            <?php echo e($errors->first()); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.post')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label fw-medium">
                    Email
                </label>

                <div class="input-group">
                    <span class="input-group-text border-end-0">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        class="form-control border-start-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="your@email.com"
                        required
                        autofocus>
                </div>
            </div>

            <div class="mb-3">

                <label class="form-label fw-medium">
                    Password
                </label>

                <div class="input-group">

                    <span class="input-group-text border-end-0">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        class="form-control border-start-0"
                        placeholder="••••••••"
                        required>

                </div>

            </div>

            <div class="mb-4">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember">

                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>

                </div>

            </div>

            <button class="btn btn-primary w-100 fw-semibold py-2" type="submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Masuk
            </button>

        </form>

        <hr class="my-4">

        <p class="text-center text-muted small mb-0">
            Belum punya akun?
            <a href="<?php echo e(route('register')); ?>" class="text-primary fw-semibold">
                Daftar sekarang
            </a>
        </p>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html><?php /**PATH C:\Users\Victus\supply-chain-platform\resources\views\auth\login.blade.php ENDPATH**/ ?>