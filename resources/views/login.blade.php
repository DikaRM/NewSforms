<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · LMS SKANIC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-card">
            <h1 class="login-title"># Login</h1>
            <h2 class="welcome-text">## Selamat Datang<br>di LMS SKANIC</h2>
            
            <div class="id-pengguna">
                ### ID Pengguna
            </div>

            <div class="form-group">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <input type="text" name="nama" class="input-field" placeholder="Masukan ID Pengguna">
                    <input type="password" name="password" class="input-field" placeholder="Masukan Password">
                    <button type="submit">Masuk</button>
                </form>
            </div>

            <div class="masuk-button">
                Masuk
            </div>
        </div>
    </div>
</body>
</html>