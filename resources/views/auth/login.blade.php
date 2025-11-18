<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Ajo Asli Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: white;
            overflow: hidden;
        }
        .card-header-custom {
            background-color: #e0f2f1;
            padding: 30px 30px 10px;
            border-bottom: none;
        }
        .app-icon {
            background: #0d6efd;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header-custom">
        <div class="app-icon"><i class="fas fa-box-open"></i></div>
        <h4 class="fw-bold mb-1">Selamat Datang</h4>
        <p class="text-muted mb-0">Masuk ke akun Toko Ajo Asli Store</p>
    </div>
    <div class="p-4 pt-2">
        @if($errors->any())
            <div class="alert alert-danger py-2 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Email</label>
                <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="you@example.com" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="••••••••" required>
                    <span class="input-group-text bg-white border-start-0"><i class="far fa-eye text-muted"></i></span>
                </div>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fs-6 fw-bold">Masuk</button>
            </div>
        </form>
    </div>
    <div class="text-center pb-4 text-muted small border-top pt-3 mx-4">
        &copy; 2025 Toko Ajo Asli Store
    </div>
</div>

</body>
</html>
