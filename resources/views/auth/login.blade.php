<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <div class="background"></div>
    <div class="card">
        <img class="logo" src="/img/logo.png" alt="Logo">
        <h4>Selamat Datang!</h4>
        
        <form class="form" method="POST" action="{{ route('login.perform') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            <input type="password" name="password" placeholder="Password" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            <button type="submit">Masuk</button>
        </form>

        <footer>
            Belum punya akun? 
            <a href="{{ route('register') }}">Daftar</a>
        </footer>
    </div>
</body>
</html>
