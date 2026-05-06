<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

/* =========================
   PROSES TAMBAH USER
========================= */

if(isset($_POST['username'])){

    $username = $_POST['username'];
    $nama     = $_POST['nama'];
    $password = $_POST['password'];
    $ruangan  = $_POST['ruangan'];
    $role     = $_POST['role'];

    $cek = mysqli_query($conn,
        "SELECT * FROM users WHERE username='$username'"
    );

    if(mysqli_num_rows($cek) > 0){

        echo "
        <script>
            alert('Username sudah digunakan!');
            window.location='proses_tambah_user.php';
        </script>
        ";

        exit;
    }

    $insert = mysqli_query($conn,
        "INSERT INTO users
        (nama, username, password, role, ruangan)
        VALUES
        ('$nama','$username','$password','$role','$ruangan')"
    );

    if($insert){

        echo "
        <script>
            alert('User berhasil ditambahkan');
            window.location='proses_tambah_user.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Gagal menambahkan user');
            window.location='proses_tambah_user.php';
        </script>
        ";

    }

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
html, body{
    height:100%;
    margin:0;
}

body{
    background:#f1f5f9;
}

   .navbar-custom{
    position: fixed;
    top: 0;
    left: 260px;
    width: calc(100% - 260px);
    z-index: 1000;

    background: linear-gradient(135deg, #0f172a, #1e293b);
    backdrop-filter: blur(10px);

    padding: 16px 28px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    border-bottom: 1px solid rgba(255,255,255,0.08);

    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.navbar-left h5{
    margin:0;
    font-size:22px;
    font-weight:700;
    color:white;
}

.navbar-left small{
    color:#94a3b8;
    font-size:13px;
}

.navbar-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.nav-icon{
    width:42px;
    height:42px;
    border-radius:12px;
    background: rgba(255,255,255,0.08);

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;
    font-size:18px;

    transition:0.3s;
    cursor:pointer;
}

.nav-icon:hover{
    background:#3b82f6;
    transform:translateY(-2px);
}

.profile-box{
    display:flex;
    align-items:center;
    gap:12px;

    background: rgba(255,255,255,0.06);
    padding:8px 14px;
    border-radius:14px;
}

.profile-avatar{
    width:42px;
    height:42px;
    border-radius:50%;
    background: linear-gradient(135deg,#3b82f6,#2563eb);

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:20px;
    color:white;
}

.profile-info{
    line-height:1.2;
}

.profile-info b{
    color:white;
    font-size:14px;
}

.profile-info small{
    color:#94a3b8;
    font-size:12px;
}

 .sidebar {
        width: 260px;
        min-height: 100vh;
        background: linear-gradient(180deg, #0f172a, #1e3a8a);
        color: white;
        padding: 20px 15px;
    }

    /* LOGO */
   .sidebar .logo{
    text-align:center;
    padding-bottom:25px;
    margin-bottom:25px;

    border-bottom:
    1px solid rgba(255,255,255,0.08);
}

    .sidebar .logo img {
        width: 70px;
    }

    .sidebar .logo h5 {
        margin-top: 10px;
        font-weight: bold;
    }

    .sidebar .logo small {
        font-size: 12px;
        opacity: 0.7;
    }

    /* MENU */
    .sidebar a{
    display:flex;
    align-items:center;
    gap:14px;

    padding:14px 16px;

    border-radius:16px;

    color:#cbd5e1;
    text-decoration:none;

    margin-bottom:10px;

    transition:0.3s;

    font-weight:500;
    position:relative;
    overflow:hidden;
}

   .sidebar a:hover{
    background:rgba(255,255,255,0.08);

    transform:translateX(5px);

    color:white;
}

   .sidebar a.active{

    background:
    linear-gradient(
        90deg,
        #2563eb,
        #3b82f6
    );

    color:white;

    box-shadow:
    0 10px 25px rgba(37,99,235,0.35);
}

    /* ICON */
   .sidebar i{
    font-size:20px;
}

    /* FOOT BOX */
    .sidebar-footer {
        margin-top: auto;
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 12px;
        text-align: center;
    }

    .sidebar-footer img {
        width: 40px;
        margin-bottom: 10px;
    }

    .sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;

    background:
    linear-gradient(
        180deg,
        #0b1120 0%,
        #172554 100%
    );

    padding:25px 18px;

    border-right:1px solid rgba(255,255,255,0.08);

    box-shadow:
    10px 0 30px rgba(0,0,0,0.15);

    z-index:999;
}

.sidebar::before{
    content:'';

    position:absolute;

    top:-100px;
    left:-100px;

    width:220px;
    height:220px;

    background:#3b82f6;

    opacity:0.15;

    filter:blur(80px);

    border-radius:50%;
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

.main-content{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.content{
    flex:1;
}

.card-box{
    background:white;
    padding:35px;
    border-radius:24px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.06);

    border:
    1px solid rgba(226,232,240,0.8);
}

/* TITLE */
.page-title{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:8px;
}

.page-subtitle{
    color:#64748b;
    margin-bottom:30px;
}

/* FORM */
.form-control,
.form-select{
    height:55px;
    border-radius:16px;
    border:1px solid #cbd5e1;

    padding-left:18px;

    font-size:15px;

    transition:0.3s;
}

.form-control:focus,
.form-select:focus{
    border-color:#3b82f6;
    box-shadow:
    0 0 0 4px rgba(59,130,246,0.12);
}

/* BUTTON */
.btn-modern{
    height:55px;

    border:none;

    border-radius:16px;

    background:
    linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );

    color:white;

    font-weight:600;
    font-size:16px;

    transition:0.3s;
}

.btn-modern:hover{
    transform:translateY(-2px);

    box-shadow:
    0 12px 25px rgba(37,99,235,0.3);
}

/* ICON INPUT */
.input-icon{
    position:relative;
}

.input-icon i{
    position:absolute;
    top:18px;
    left:18px;

    color:#94a3b8;
}

.input-icon input,
.input-icon select{
    padding-left:50px;
}

/* HEADER CARD */
.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:25px;
}

.form-badge{
    background:#dbeafe;
    color:#2563eb;

    padding:8px 16px;

    border-radius:999px;

    font-size:13px;
    font-weight:600;
}

/* FOOTER */
  /* FOOTER MODERN */
.footer-custom{
    margin-left:260px;

    background:
    linear-gradient(
        135deg,
        #1e293b,
        #334155
    );

    color:white;

    padding:45px 40px 20px;

    margin-top:40px;

    border-top:
    1px solid rgba(255,255,255,0.08);
}

/* CONTAINER */
.footer-inner{
    width:100%;
}

/* CONTENT */
.footer-content{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;

    gap:40px;

    flex-wrap:wrap;
}

/* LEFT */
.footer-left{
    display:flex;
    align-items:flex-start;
    gap:18px;

    max-width:500px;
}

.footer-left img{
    width:65px;
}

/* TEXT */
.footer-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:8px;
}

.footer-desc{
    color:#cbd5e1;
    line-height:1.7;
    font-size:14px;
}

/* RIGHT */
.footer-right{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.footer-item{
    display:flex;
    align-items:center;
    gap:12px;

    color:#e2e8f0;
    font-size:15px;
}

.footer-item i{
    color:#60a5fa;
    font-size:18px;
}

/* BOTTOM */
.footer-bottom{
    margin-top:35px;
    padding-top:20px;

    border-top:
    1px solid rgba(255,255,255,0.08);

    text-align:center;

    color:#94a3b8;
    font-size:14px;
}

/* RESPONSIVE */
@media(max-width:768px){

    .footer-custom{
        margin-left:0;
        padding:30px 20px;
    }

    .footer-content{
        flex-direction:column;
    }

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
    <div class="navbar-custom">

    <!-- LEFT -->
    <div class="navbar-left">

        <small>Selamat Datang 👋</small>

        <h5>
            Admin
        </h5>

    </div>

    <!-- RIGHT -->
    <div class="navbar-right">


        <!-- PROFILE -->
        <div class="profile-box">

            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>

            <div class="profile-info">
                <b><?= $_SESSION['username']; ?></b><br>
                <!-- <small>Administrator</small> -->
            </div>

        </div>

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
    <i class="bi bi-grid-1x2-fill"></i>
    <span>Dashboard</span>
</a>

<a href="rekap_barang.php">
    <i class="bi bi-box-seam-fill"></i>
    <span>Rekap Barang</span>
</a>

<a href="proses_tambah_user.php">
    <i class="bi bi-people-fill"></i>
    <span>Tambah User</span>
</a>

<a href="../auth/logout.php" class="text-danger">
    <i class="bi bi-box-arrow-right"></i>
    <span>Logout</span>
</a>
    <!-- FOOT SIDEBAR -->
    <!-- <div class="sidebar-footer mt-4">
        <img src="simbak.jpeg">
        <div><b>SIMBAK</b></div>
        <small>Sistem inventaris terintegrasi</small>
    </div> -->

</div>

<!-- CONTENT -->
 <!-- MAIN CONTENT -->
<div class="main-content">

<!-- CONTENT -->
<!-- <div class="content"> -->
<div class="content">

<div class="card-box">

    <!-- HEADER -->
    <div class="form-header">

        <div>
            <div class="page-title">
                Tambah User Baru
            </div>

            <div class="page-subtitle">
                Tambahkan akun user untuk sistem inventaris SIMBAK
            </div>
        </div>

        <div class="form-badge">
            Administrator
        </div>

    </div>

    <!-- FORM -->
    <form method="POST" action="proses_tambah_user.php" class="row g-4">

        <!-- USERNAME -->
        <div class="col-md-4">

            <div class="input-icon">
                <i class="bi bi-person"></i>

                <input 
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Username"
                    required>
            </div>

        </div>

        <!-- NAMA -->
        <div class="col-md-4">

            <div class="input-icon">
                <i class="bi bi-card-text"></i>

                <input 
                    type="text"
                    name="nama"
                    class="form-control"
                    placeholder="Nama Lengkap"
                    required>
            </div>

        </div>

        <!-- PASSWORD -->
        <div class="col-md-4">

            <div class="input-icon">
                <i class="bi bi-lock"></i>

                <input 
                    type="text"
                    name="password"
                    class="form-control"
                    placeholder="Password"
                    required>
            </div>

        </div>

        <!-- RUANGAN -->
        <div class="col-md-4">

            <div class="input-icon">
                <i class="bi bi-building"></i>

                <select name="ruangan" class="form-select" required>

                    <option value="">
                        Pilih Ruangan
                    </option>

                    <option value="Tata Usaha">
                        Tata Usaha
                    </option>

                    <option value="Inteldakim">
                        Inteldakim
                    </option>

                    <option value="Tikkim">
                        Tikkim
                    </option>

                     <option value="Lantaskim">
                        Lantaskim
                    </option>

                    <option value="Intaltuskim">
                        Intaltuskim
                    </option>

                </select>

            </div>

        </div>

        <!-- ROLE -->
        <div class="col-md-4">

            <div class="input-icon">
                <i class="bi bi-shield-lock"></i>

                <select name="role" class="form-select">

                    <option value="user">
                        User
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                </select>

            </div>

        </div>

        <!-- BUTTON -->
        <div class="col-md-4">

            <button class="btn-modern w-100">

                <i class="bi bi-plus-circle me-2"></i>

                Tambah User

            </button>

        </div>

    </form>

</div>

</div> <!-- content -->

</div> <!-- main-content -->

<footer class="footer-custom">

    <div class="footer-inner">

        <div class="footer-content">

            <!-- LEFT -->
            <div class="footer-left">

                <img src="polos.png">

                <div>

                    <div class="footer-title">
                        SIMBAK
                    </div>

                    <div class="footer-desc">
                        Sistem Inventaris Barang Masuk & Keluar
                        Kantor Imigrasi Kelas I TPI Samarinda.
                        Sistem ini membantu pengelolaan inventaris
                        menjadi lebih cepat, modern, dan terintegrasi.
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="footer-right">

                <div class="footer-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    Samarinda, Kalimantan Timur
                </div>

                <div class="footer-item">
                    <i class="bi bi-telephone-fill"></i>
                    0811-5565-000
                </div>

                <div class="footer-item">
                    <i class="bi bi-envelope-fill"></i>
                    kanim_samarinda@imigrasi.go.id
                </div>

            </div>

        </div>

        <!-- BOTTOM -->
        <div class="footer-bottom">
            © 2026 SIMBAK Inventory System
        </div>

    </div>

</footer>

</body>
</html>