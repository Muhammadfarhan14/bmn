<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tambah User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{ background:#f1f5f9; }

    .navbar-custom {
    background: linear-gradient(90deg, #1e293b, #334155);
    color: white;
    padding: 15px 25px;
    border-bottom: 3px solid #20c997;
}

.navbar-custom{
    position:fixed;
    top:0;
    left:260px;
    width:calc(100% - 260px);
    z-index:1000;
}

 .sidebar{
    width:260px;
    min-height:100vh;
    background: linear-gradient(180deg, #0f172a, #1e3a8a);
    color:white;
    padding:20px 15px;
}

/* LOGO */
.sidebar .logo{
    text-align:center;
    margin-bottom:25px;
}

.sidebar .logo img{
    width:70px;
}

.sidebar .logo h5{
    margin-top:10px;
    font-weight:bold;
}

.sidebar .logo small{
    font-size:12px;
    opacity:0.7;
}

/* MENU */
.sidebar a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px;
    border-radius:12px;
    color:#e2e8f0;
    text-decoration:none;
    margin-bottom:8px;
    transition:0.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.1);
}

.sidebar a.active{
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    color:white;
}

/* ICON */
.sidebar i{
    font-size:18px;
}

/* FOOT BOX */
.sidebar-footer{
    margin-top:auto;
    background:rgba(255,255,255,0.1);
    padding:15px;
    border-radius:12px;
    text-align:center;
}

.sidebar-footer img{
    width:40px;
    margin-bottom:10px;
}

.sidebar{
    width:260px;
    height:100vh; /* penting */
    position:fixed; /* biar full dan nempel */
    top:0;
    left:0;
    background: linear-gradient(180deg, #0f172a, #1e3a8a);
    color:white;
    padding:20px 15px;
    overflow-y:auto;
}

.content{
    margin-left:260px;
    padding:20px;
}

.content{
    margin-left:260px;
    padding:20px;
    margin-top:80px;
}

.content{
    margin-left:260px;
    margin-top:80px;
    padding:20px;
}

.card-box{
    background:white;
    padding:20px;
    border-radius:12px;
}

/* FOOTER */
  .footer-custom {
        width: 100%;
        background: #334155;
        color: white;
        padding: 30px 0;
    }

    .footer-inner {
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .footer-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .footer-left img {
        width: 60px;
    }

    .footer-right p {
        margin: 5px 0;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 10px;
    }
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-custom d-flex justify-content-between align-items-center flex-wrap">

    <!-- KIRI (LOGO + NAMA SISTEM) -->
    <!-- <div class="d-flex align-items-center gap-3">
        <img src="logo.png" width="45">
        <div>
            <b style="font-size:18px;">SIMBAK</b><br>
            <small style="font-size:12px; opacity:0.8;">
                Sistem Inventaris Barang Masuk & Keluar
            </small>
        </div>
    </div> -->

    <!-- TENGAH (SAPAAN) -->
    <div class="text-center d-none d-md-block">
        <div style="font-size:14px;">Selamat Datang,</div>
        <b style="font-size:18px;">
            <?= $_SESSION['username']; ?> 👋
        </b>
    </div>

    <!-- KANAN (INFO + USER) -->
    <div class="d-flex align-items-center gap-4">

        <!-- TANGGAL -->
        <!-- <div class="text-end d-none d-md-block">
            <div style="font-size:13px;">
                <i class="bi bi-calendar"></i>
                <?= date('d M Y') ?>
            </div>
            <small style="font-size:12px;">
                <?= date('H:i') ?> WIB
            </small>
        </div> -->

        <!-- NOTIF -->
        <!-- <div class="position-relative">
            <i class="bi bi-bell fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                <?= $total ?>
            </span>
        </div> -->

        <!-- USER -->
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-5"></i>
            <div>
                <div style="font-size:13px;"><?= $_SESSION['username']; ?></div>
                <!-- <small style="font-size:11px; opacity:0.7;">Administrator</small> -->
            </div>
        </div>

        <!-- LOGOUT -->
        <!-- <a href="../auth/logout.php" class="btn btn-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i>
        </a> -->

    </div>
    
</div>

<!-- WRAPPER -->
<div class="wrapper">

    <!-- SIDEBAR -->
   <div class="sidebar d-flex flex-column">

    <!-- LOGO -->
    <div class="logo">
        <img src="polos.png">
        <h5>SIMBAK</h5>
        <small>Sistem Inventaris Barang Masuk & Keluar</small>
    </div>

    <!-- MENU -->
    <a href="dashboard.php" class="active">
        <i class="bi bi-house-door"></i> Dashboard
    </a>

    <a href="rekap_barang.php">
        <i class="bi bi-box"></i> Rekap Barang
    </a>

    <a href="proses_tambah_user.php">
        <i class="bi bi-gear"></i> Tambah User
    </a>

    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

    <!-- FOOT SIDEBAR -->
    <div class="sidebar-footer mt-4">
        <img src="simbak.jpeg">
        <div><b>SIMBAK</b></div>
        <small>Sistem inventaris terintegrasi</small>
    </div>

</div>

<!-- CONTENT -->
<div class="content">

<div class="card-box">
    <h4>Tambah User Baru</h4>

    <form method="POST" action="proses_tambah_user.php" class="row g-3">

        <div class="col-md-4">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="col-md-4">
            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
        </div>

        <div class="col-md-4">
            <input type="text" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="col-md-4">
            <select name="ruangan" class="form-select" required>
                <option value="">Pilih Ruangan</option>
                <option value="TIKIM">TIKIM</option>
                <option value="LANTAS">LANTAS</option>
                <option value="ARSIP">ARSIP</option>
            </select>
        </div>

        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-success w-100">Tambah</button>
        </div>

    </form>
</div>

</div>

</body>
</html>