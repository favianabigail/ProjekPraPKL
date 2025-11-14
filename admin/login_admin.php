<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../login.css">


</head>
<body>
    <form action="proses_login_admin.php" method="post">
    <div class="login-box">
        <div class="login-header">
            <header>Login Admin</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Nama Admin" name="nama" required>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" name="password" required>
        </div>
        <div class="input-submit">
            <input type="submit" value="Login">
        </div>
    </div>
</form>
</body>
</html>

