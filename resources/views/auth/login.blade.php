<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #6B46C1 0%, #4F46E5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(15px);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .icon-container i {
            font-size: 3.5rem;
            background: linear-gradient(45deg, #6B46C1, #4F46E5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        h4 {
            color: #1A1A1A;
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 2.5rem;
            font-weight: 600;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .form input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #FFFFFF;
            box-sizing: border-box;
            height: 3.5rem;
        }

        .form input:focus {
            outline: none;
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        .error-message {
            color: #DC2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(45deg, #6B46C1, #4F46E5);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            height: 3.5rem;
            margin-top: 0.5rem;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.4);
        }

        footer {
            text-align: center;
            margin-top: 2.5rem;
            color: #4B5563;
            font-size: 1rem;
        }

        footer a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding-bottom: 2px;
            border-bottom: 2px solid transparent;
        }

        footer a:hover {
            color: #6B46C1;
            border-bottom: 2px solid #6B46C1;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-container">
            <a href="/">
                <i class="fas fa-bus"></i>
            </a>
        </div>
        <h4>Selamat Datang Kembali</h4>
        
        <form class="form" method="POST" action="{{ route('login.perform') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Masukkan password Anda" required>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
            
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i>
                <span>Masuk</span>
            </button>
        </form>

        <footer>
            Belum punya akun? 
            <a href="{{ route('register') }}">Daftar Sekarang</a>
        </footer>
    </div>
</body>
</html>