<?php
session_start();
include '../config/koneksi.php';

/* VALIDASI */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

/* NOTIF */
$data_pinjam = mysqli_query($conn,"
SELECT p.*, b.nama_barang, u.username
FROM peminjaman p
JOIN barang b ON p.barang_id = b.id
JOIN users u ON p.user_id = u.id
WHERE p.status='pending'
");


$total = mysqli_num_rows($data_pinjam);

// TOTAL BARANG
$total_barang = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang"));

// TOTAL TERSEDIA
$tersedia = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang WHERE jumlah > 0"));

// TOTAL HABIS
$habis = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang WHERE jumlah = 0"));

// TOTAL PEMINJAMAN BULAN INI
$bulan = date('Y-m');
$total_pinjam = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM peminjaman 
WHERE status='disetujui' 
AND DATE_FORMAT(created_at,'%Y-%m')='$bulan'
"));

/* AKSI */
if(isset($_GET['aksi'])){
    $id = $_GET['id'];

    if($_GET['aksi'] == 'terima'){
        $ambil = mysqli_query($conn,"SELECT * FROM peminjaman WHERE id='$id'");
        $p = mysqli_fetch_assoc($ambil);

        mysqli_query($conn,"UPDATE barang 
        SET jumlah = jumlah - $p[jumlah]
        WHERE id = $p[barang_id]");

        mysqli_query($conn,"UPDATE peminjaman SET status='disetujui' WHERE id='$id'");
    } else {
        mysqli_query($conn,"UPDATE peminjaman SET status='ditolak' WHERE id='$id'");
    }

    header("Location: dashboard.php");
    exit;
}

/* TAMBAH */
if(isset($_POST['simpan'])){
    mysqli_query($conn,"INSERT INTO barang 
    (kode_barang, kategori, status, nama_barang, jumlah, lokasi, kondisi) 
    VALUES (
        '$_POST[kode_barang]',
        '$_POST[kategori]',
        '".($_POST['jumlah']>0?'Tersedia':'Habis')."',
        '$_POST[nama]',
        '$_POST[jumlah]',
        'Gudang',
        'baik'
    )");

    header("Location: dashboard.php");
    exit;
}

/* UPDATE */
if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE barang SET

        kode_barang='$_POST[kode_barang]',
        nama_barang='$_POST[nama]',
        kategori='$_POST[kategori]',
        jumlah='$_POST[jumlah]',

        status='".($_POST['jumlah'] > 0 
            ? 'Tersedia' 
            : 'Habis')."'

    WHERE id='$_POST[id]'
    ");

    header("Location: dashboard.php");
    exit;
}

/* =======================
   TANDAI DIBACA
======================= */
if(isset($_GET['baca'])){
    $id = $_GET['baca'];

    mysqli_query($conn,"UPDATE catatan_user 
    SET status='dibaca' WHERE id='$id'");

    echo "<script>location='dashboard.php';</script>";
}

/* =======================
   HAPUS CATATAN
======================= */
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    mysqli_query($conn,"DELETE FROM catatan_user 
    WHERE id='$id'");

    echo "<script>location='dashboard.php';</script>";
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet"
href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet"
href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
    body {
        background: #f1f5f9;
    }

    /* NAVBAR
.navbar-custom{
    background:#334155;
    color:white;
    padding:15px 25px;
} */

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

    /* WRAPPER */
    .wrapper {
        display: flex;
        min-height: 100vh;
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


    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
        margin-top: 80px;
    }

    /* CONTENT */
    .content {
        flex: 1;
        padding: 20px;
    }

    /* CARD */
    .card-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    /* =========================
   MODERN TABLE CARD
========================= */

.modern-card{
    background:#ffffff;
    border-radius:24px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    border:1px solid #eef2f7;
    margin-bottom:25px;
}

/* HEADER */
.modern-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.modern-title{
    font-size:24px;
    font-weight:700;
    color:#0f172a;
}

.modern-subtitle{
    color:#64748b;
    font-size:14px;
}

/* TABLE */
.table-modern{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
}

.table-modern thead th{
    background:#f8fafc;
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    padding:16px;
    border:none;
    font-weight:700;
}

.table-modern tbody td{
    padding:18px 16px;
    border-bottom:1px solid #f1f5f9;
    vertical-align:middle;
}

.table-modern tbody tr{
    transition:0.3s;
}

.table-modern tbody tr:hover{
    background:#f8fafc;
}

/* BADGE */
.badge-new{
    background:#fef3c7;
    color:#d97706;
    padding:7px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.badge-read{
    background:#dcfce7;
    color:#15803d;
    padding:7px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

/* BUTTON */
.btn-modern-success{
    width:38px;
    height:38px;
    border:none;
    border-radius:12px;
    background:#16a34a;
    color:white;
    transition:0.3s;
}

.btn-modern-success:hover{
    transform:translateY(-2px);
    background:#15803d;
}

.btn-modern-danger{
    width:38px;
    height:38px;
    border:none;
    border-radius:12px;
    background:#ef4444;
    color:white;
    transition:0.3s;
}

.btn-modern-danger:hover{
    transform:translateY(-2px);
    background:#dc2626;
}

/* ICON BOX */
.icon-circle{
    width:55px;
    height:55px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
}

/* REQUEST BOX */
.request-icon{
    background:#dbeafe;
    color:#2563eb;
}

.note-icon{
    background:#f3e8ff;
    color:#9333ea;
}
    /* BADGE */
    .badge-aktif {
        background: #d1fae5;
        color: #065f46;
        padding: 6px 10px;
        border-radius: 20px;
    }

    /* FOOTER */
    /* .footer-custom {
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
    } */

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

    .card-box h3 {
        font-weight: bold;
        color: #20c997;
    }


    /* RESPONSIVE */
    @media(max-width:768px) {
        .wrapper {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
        }
    }

    .card-box {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .card-box {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .card-box:hover {
        transform: translateY(-3px);
    }

    .text-purple {
        color: #9333ea;
    }

    .table-wrapper{
    background:#fff;
    border-radius:18px;
    padding:20px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

/* TOP DATA TABLE */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:20px;
}

/* SEARCH */
.dataTables_filter input{
    border:1px solid #e5e7eb !important;
    border-radius:12px !important;
    padding:8px 14px !important;
    background:#f8fafc !important;
}

/* SELECT SHOW */
.dataTables_length select{
    border-radius:12px !important;
    border:1px solid #e5e7eb !important;
    padding:6px 10px !important;
    background:#f8fafc !important;
}

/* TABLE */
#tableBarang{
    width:100% !important;
    border-collapse:separate;
    border-spacing:0;
}

/* HEADER */
#tableBarang thead th{
    background:#f8fafc !important;
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    border:none !important;
    padding:18px 14px !important;
    font-weight:700;
}

/* BODY */
#tableBarang tbody td{
    padding:18px 14px !important;
    border-bottom:1px solid #f1f5f9 !important;
    vertical-align:middle;
    font-size:15px;
}

/* HOVER */
#tableBarang tbody tr{
    transition:0.2s;
}

#tableBarang tbody tr:hover{
    background:#f8fafc;
}

/* STATUS */
.badge-modern{
    background:#dcfce7;
    color:#15803d;
    padding:8px 15px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

/* BUTTON */
.btn-action{
    width:38px;
    height:38px;
    border:none;
    border-radius:10px;
    color:white;
}

.btn-edit{
    background:#facc15;
}

.btn-delete{
    background:#ef4444;
}

/* PAGINATION */
.dataTables_wrapper .dataTables_paginate{
    margin-top:20px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{
    border:none !important;
    background:transparent !important;
    border-radius:10px !important;
    margin:0 4px;
    padding:6px 14px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current{
    background:#4f46e5 !important;
    color:white !important;
}

/* INFO */
.dataTables_info{
    margin-top:15px;
    color:#64748b;
}

/* WRAPPER */
.dataTables_wrapper{
    margin-top:10px;
}

/* TOP AREA */
.dataTables_length,
.dataTables_filter{
    margin-bottom:20px;
}

/* SHOW */
.dataTables_length label{
    font-weight:600;
    color:#64748b;
}

.dataTables_length select{
    border:none !important;
    background:#f8fafc !important;
    border-radius:12px !important;
    padding:10px 15px !important;
    min-width:140px;
    font-weight:600;
}

/* SEARCH */
.dataTables_filter input{
    border:none !important;
    background:#f8fafc !important;
    border-radius:12px !important;
    padding:10px 15px !important;
    margin-left:10px !important;
}

/* BUTTON */
.dt-buttons{
    margin-bottom:20px;
}

.dt-button{
    background:#4f46e5 !important;
    border:none !important;
    color:white !important;
    border-radius:12px !important;
    padding:10px 22px !important;
    font-weight:600 !important;
    transition:0.3s;
}

.dt-button:hover{
    background:#4338ca !important;
}

/* PAGINATION */
.dataTables_paginate{
    margin-top:20px !important;
}

.paginate_button{
    border:none !important;
    background:transparent !important;
    border-radius:12px !important;
    margin:0 4px !important;
    padding:8px 14px !important;
    transition:0.3s;
}

.paginate_button.current{
    background:#4f46e5 !important;
    color:white !important;
}

/* INFO */
.dataTables_info{
    margin-top:18px;
    color:#64748b;
}

/* TABLE */
#tableBarang{
    border-collapse:separate;
    border-spacing:0;
}

#tableBarang thead th{
    background:#f8fafc;
    border:none !important;
    text-transform:uppercase;
    font-size:13px;
    color:#64748b;
    padding:18px !important;
}

#tableBarang tbody td{
    padding:20px 18px !important;
    border-bottom:1px solid #f1f5f9 !important;
}

#tableBarang tbody tr:hover{
    background:#f8fafc;
}

.modal-content{
    border:none;
    border-radius:20px;
    overflow:hidden;
}

.modal-header{
    border-bottom:none;
    padding:20px 25px;
}

.modal-body{
    padding:25px;
}

.modal-footer{
    border-top:none;
    padding:20px 25px;
}

.modal .form-control,
.modal .form-select{
    height:50px;
    border-radius:14px;
}

.modal .btn-success{
    border-radius:12px;
    padding:10px 25px;
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
        <div class="content">
            <div class="row mb-4 g-3">

                <!-- TOTAL BARANG -->
                <div class="col-md-3">
                    <div class="card-box d-flex align-items-center gap-3">

                        <div style="
                width:70px;
                height:70px;
                border-radius:20px;
                background:#dcfce7;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:35px;
                color:#16a34a;
            ">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div>
                            <small class="text-muted">Total Barang</small>
                            <h2 class="mb-0 text-success">
                                <?= $total_barang ?>
                            </h2>
                            <small class="text-muted">
                                Keseluruhan
                            </small>
                        </div>

                    </div>
                </div>

                <!-- TERSEDIA -->
                <div class="col-md-3">
                    <div class="card-box d-flex align-items-center gap-3">

                        <div style="
                width:70px;
                height:70px;
                border-radius:20px;
                background:#dbeafe;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:35px;
                color:#2563eb;
            ">
                            <i class="bi bi-check-circle"></i>
                        </div>

                        <div>
                            <small class="text-muted">Tersedia</small>
                            <h2 class="mb-0 text-primary">
                                <?= $tersedia ?>
                            </h2>
                            <small class="text-muted">
                                Barang tersedia
                            </small>
                        </div>

                    </div>
                </div>

                <!-- HABIS -->
                <div class="col-md-3">
                    <div class="card-box d-flex align-items-center gap-3">

                        <div style="
                width:70px;
                height:70px;
                border-radius:20px;
                background:#fef3c7;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:35px;
                color:#d97706;
            ">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>

                        <div>
                            <small class="text-muted">Habis</small>
                            <h2 class="mb-0 text-warning">
                                <?= $habis ?>
                            </h2>
                            <small class="text-muted">
                                Barang kosong
                            </small>
                        </div>

                    </div>
                </div>

                <!-- PEMINJAMAN -->
                <div class="col-md-3">
                    <div class="card-box d-flex align-items-center gap-3">

                        <div style="
                width:70px;
                height:70px;
                border-radius:20px;
                background:#f3e8ff;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:35px;
                color:#9333ea;
            ">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <small class="text-muted">Peminjaman</small>
                            <h2 class="mb-0 text-purple">
                                <?= $total_pinjam ?>
                            </h2>
                            <small class="text-muted">
                                Bulan ini
                            </small>
                        </div>

                    </div>
                </div>

            </div>

            <div class="card-box mb-3">
                <h5>Catatan dari User</h5>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <th>User</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                        <?php
        $catatan = mysqli_query($conn,"
        SELECT c.*, u.username 
        FROM catatan_user c
        JOIN users u ON c.user_id = u.id
        ORDER BY c.id DESC
        ");

        if(mysqli_num_rows($catatan) > 0){
        while($c = mysqli_fetch_assoc($catatan)){
        ?>
                        <tr>
                            <td><?= $c['username'] ?></td>

                            <td><?= $c['isi_catatan'] ?></td>

                            <td>
                                <?php 
$status = isset($c['status']) ? $c['status'] : 'baru';

if($status == 'baru'){ ?>
                                <span class="badge bg-warning">Baru</span>
                                <?php } else { ?>
                                <span class="badge bg-success">Dibaca</span>
                                <?php } ?>
                            </td>

                            <td>
                                <!-- TANDAI DIBACA -->
                                <a href="?baca=<?= $c['id'] ?>" class="btn btn-success btn-sm">✔</a>

                                <!-- HAPUS -->
                                <a href="?hapus=<?= $c['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus catatan?')">✖</a>
                            </td>
                        </tr>
                        <?php }} else { ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada catatan
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- PINJAMAN -->
            <div class="card-box mb-3">
                <h5>Permintaan Peminjaman</h5>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>User</th>
                                <th>Catatan</th> <!-- 🔥 -->
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                if(mysqli_num_rows($data_pinjam) > 0){
                while($p = mysqli_fetch_assoc($data_pinjam)){
                ?>
                            <tr>
                                <td><?= $p['nama_barang'] ?></td>
                                <td><?= $p['jumlah'] ?></td>
                                <td><?= $p['username'] ?></td>
                                <td><?= $p['catatan'] ?></td> <!-- 🔥 -->

                                <td>
                                    <a href="dashboard.php?aksi=terima&id=<?= $p['id'] ?>"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Terima permintaan ini?')">✔</a>

                                    <a href="dashboard.php?aksi=tolak&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tolak permintaan ini?')">✖</a>
                                </td>
                            </tr>
                            <?php }} else { ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Tidak ada permintaan
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- HEADER -->
            <!-- CARD HEADER + TABLE MENJADI SATU -->
<div class="card-box table-wrapper">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <!-- KIRI -->
        <div>
            <h4 class="mb-1 fw-bold">Dashboard Admin</h4>
            <small class="text-muted">Sistem Inventaris</small>
        </div>

        <!-- KANAN -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- TOMBOL -->
            <button 
                class="btn btn-success px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah"
            >
                + Tambah Barang
            </button>

        </div>

    </div>

    <!-- TABLE -->
    <table id="tableBarang" class="table align-middle">

        <thead>
            <tr>
                <th style="display:none;">ID</th>
                <th>Kode</th>
                <th>Status</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM barang ORDER BY id DESC");

            while($d=mysqli_fetch_assoc($data)){
            ?>

            <tr>

            <td style="display:none;">
        <?= $d['id']; ?>
    </td>

                <!-- KODE -->
                <td>
                    <strong><?= $d['kode_barang']; ?></strong>
                </td>

                <!-- STATUS -->
                <td>

                    <?php if($d['jumlah'] > 0){ ?>

                        <span class="badge-modern">
                            Tersedia
                        </span>

                    <?php } else { ?>

                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            Habis
                        </span>

                    <?php } ?>

                </td>

                <!-- NAMA -->
                <td>
                    <?= $d['nama_barang']; ?>
                </td>

                <!-- KATEGORI -->
                <td>
                    <?= $d['kategori']; ?>
                </td>

                <!-- JUMLAH -->
                <td>
                    <strong><?= $d['jumlah']; ?></strong>
                </td>

                <!-- AKSI -->
                <td>

                    <div class="d-flex gap-2">

                        <!-- EDIT -->
                        <button 
                            class="btn-action btn-edit"

                            onclick="editData(
                                '<?= $d['id'] ?>',
                                '<?= $d['kode_barang'] ?>',
                                '<?= $d['nama_barang'] ?>',
                                '<?= $d['jumlah'] ?>',
                                '<?= $d['kategori'] ?>'
                            )"
                        >

                            <i class="bi bi-pencil-fill"></i>

                        </button>

                        <!-- DELETE -->
                        <a 
                            href="hapus_barang.php?id=<?= $d['id'] ?>"
                            class="btn-action btn-delete d-flex align-items-center justify-content-center"
                            onclick="return confirm('Hapus barang ini?')"
                        >

                            <i class="bi bi-trash-fill"></i>

                        </a>

                    </div>

                </td>

            </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-body">
                        <input type="text" name="kode_barang" class="form-control mb-2" placeholder="Kode">
                        <input type="text" name="nama" class="form-control mb-2" placeholder="Nama">
                        <input type="number" name="jumlah" class="form-control mb-2" placeholder="Jumlah">
                        <select name="kategori" class="form-control">
                            <option>ATK</option>
                            <option>Elektronik</option>
                            <option>Persediaan</option>
                            <option>Peralatan</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" name="simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5>Edit Barang</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <input type="text" name="kode_barang" id="edit_kode" class="form-control mb-2"
                            placeholder="Kode">

                        <input type="text" name="nama" id="edit_nama" class="form-control mb-2" placeholder="Nama">

                        <input type="number" name="jumlah" id="edit_jumlah" class="form-control mb-2"
                            placeholder="Jumlah">

                        <select name="kategori" id="edit_kategori" class="form-select">
                            <option>ATK</option>
                            <option>Elektronik</option>
                            <option>Persediaan</option>
                            <option>Peralatan</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" name="update">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
   <!-- FOOTER -->
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



   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function editData(id, kode, nama, jumlah, kategori) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_kode').value = kode;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_kategori').value = kategori;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
    </script>

    <script>
    setInterval(() => {
        const now = new Date();
        document.querySelectorAll(".jam").forEach(el => {
            el.innerHTML = now.toLocaleTimeString();
        });
    }, 1000);
    </script>

    <script>
    $(document).ready(function() {

       $('#tableBarang').DataTable({

    pageLength: 50,

    order: [[0, 'desc']], // URUTKAN BERDASARKAN ID TERBARU

    columnDefs: [
        {
            targets: 0,
            visible: false,
            searchable: false
        }
    ],

    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"]
    ],

    language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        paginate: {
            previous: "Prev",
            next: "Next"
        },
        zeroRecords: "Data tidak ditemukan"
    }

});

    });
    </script>

    <script>

/* =========================
   AUTO REFRESH DASHBOARD
========================= */

// setInterval(function(){

//     location.reload();

// }, 10000); // 10 detik

</script>

</body>

</html>