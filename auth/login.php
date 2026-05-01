<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    height: 100vh;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-box {
    width: 350px;
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
}

.logo {
    width: 100px;
    height: 100px;
    object-fit: contain;
    margin-bottom: 15px;
    filter: drop-shadow(0 5px 10px rgba(0,0,0,0.2));
}

.btn-login {
    background: #2563eb;
    color: white;
}
</style>
</head>

<body>

<div class="login-box">
   <img src="logo.png" class="logo" alt="Logo Imigrasi">

    <h5><b>LOGIN PANEL</b></h5>
    <small>Sistem Inventaris Barang</small>

    <form method="POST" action="proses_login.php" class="mt-3">

        <div class="mb-2 text-start">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-login w-100">Masuk</button>
    </form>
</div>

</body>
</html>