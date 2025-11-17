<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<form action="proses_login.php" method="post">
    <div class="login-box">
        <div class="login-header">
            <header>Login Pelanggan</header>
        </div>
        <div class="input-box">
            <input type="email" class="input-field" placeholder="Email" name="email" required>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" name="password" required>
        </div>
        <div class="input-submit">
            <input type="submit" name="login" value="Login">
        </div>
        <div class="sign-up-link">
            <p>Belum punya akun? <a href="daftar.php">Daftar</a></p>
        </div>
    </div>
</form>
</body>
</html>
