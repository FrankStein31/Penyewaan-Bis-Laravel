<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/auth.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 120px;
            margin: 0 auto 1.5rem;
            display: block;
            transition: transform 0.3s;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        h4 {
            color: #2d3748;
            text-align: center;
            font-size: 1.75rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .form input {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.9);
        }

        .form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: -0.75rem;
            margin-bottom: 1rem;
            padding-left: 0.5rem;
        }

        button {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: #4a5568;
            font-size: 0.95rem;
        }

        footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="card">
        <img class="logo" src="/img/logo.png" alt="Logo">
        <h4>Selamat Datang Kembali</h4>
        
        <form class="form" method="POST" action="{{ route('login.perform') }}">
            @csrf
            <input type="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
            
            <input type="password" name="password" placeholder="Masukkan password Anda" required>
            @error('password')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
            
            <button type="submit">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <footer>
            Belum punya akun? 
            <a href="{{ route('register') }}">Daftar Sekarang</a>
        </footer>
    </div>
</body>
</html>
