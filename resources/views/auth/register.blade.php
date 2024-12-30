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
        <h4>Daftar Akun</h4>
        
        <form class="form" method="POST" action="{{ route('register.perform') }}">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            <input type="password" name="password" placeholder="Password" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
            
            <button type="submit">Daftar</button>
        </form>

        <footer>
            Sudah punya akun? 
            <a href="{{ route('login') }}">Masuk</a>
        </footer>
    </div>
</body>
</html>
