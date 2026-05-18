<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - ChatApp</title>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333333;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f0f0;
            padding: 40px 30px;
            width: 100%;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #333333;
            margin-bottom: 8px;
        }

        .auth-header p {
            font-size: 14px;
            color: #888888;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background-color: #ffffff;
            color: #333333;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.15);
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #ff69b4;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 105, 180, 0.2);
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #ff479d;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 105, 180, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }

        .auth-footer a {
            color: #ff69b4;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: #ff479d;
            text-decoration: underline;
        }

        .alert-error {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            color: #c53030;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-error ul {
            list-style: none;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Daftar Akun Baru</h1>
                <p>Mulai mengobrol secara real-time</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Contoh: budis123" value="{{ old('username') }}" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-primary">Daftar Sekarang</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="#">Masuk di sini</a>
            </div>
        </div>
    </div>

</body>
</html>
