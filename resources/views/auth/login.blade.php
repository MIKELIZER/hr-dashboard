<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            background: linear-gradient(135deg, #4361ee, #7209b7);
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
        }
        .login-header .icon-circle {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.6rem;
        }
        .login-body {
            padding: 32px;
            background: white;
        }
        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .btn-login {
            background: linear-gradient(135deg, #4361ee, #7209b7);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="icon-circle"><i class="bi bi-shield-lock-fill"></i></div>
        <h3 class="mb-1 fw-bold">HR Dashboard</h3>
        <p class="text-white-50 small mb-0">Masuk ke portal Anda</p>
    </div>
    <div class="login-body">
        @if (session('status'))
            <div class="alert alert-success mb-3 text-center small">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label form-label-custom">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                </div>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label form-label-custom">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" type="password" name="password" placeholder="••••••••" required>
                </div>
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="form-check mb-4">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-muted small">Ingat saya</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-login btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
