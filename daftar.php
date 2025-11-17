<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<form action="proses_daftar.php" method="post">
    <div class="login-box">
        <div class="login-header">
            <header>Daftar</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Nama" autocomplete="off" required name="nama">
        </div>
        <div class="input-box">
            <input type="email" class="input-field" placeholder="Email" autocomplete="off" required name="email">
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" autocomplete="off" required name="password">
        </div>
        <div class="input-submit">
            <input type="submit" name="daftar" value="Daftar">
        </div>
        <div class="sign-up-link">
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</form>
</body>
</html>
