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
    mysqli_query($conn,"UPDATE barang SET
        kode_barang='$_POST[kode_barang]',
        nama_barang='$_POST[nama]',
        jumlah='$_POST[jumlah]',
        status='".($_POST['jumlah']>0?'Tersedia':'Habis')."'
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

    .navbar-custom {
        background: linear-gradient(90deg, #1e293b, #334155);
        color: white;
        padding: 15px 25px;
        border-bottom: 3px solid #20c997;
    }

    .navbar-custom {
        position: fixed;
        top: 0;
        left: 260px;
        width: calc(100% - 260px);
        z-index: 1000;
    }

    /* WRAPPER */
    .wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* SIDEBAR
.sidebar{
    width:230px;
    background:#fff;
    padding:20px;
    border-right:1px solid #eee;
}

.sidebar a{
    display:block;
    padding:10px;
    margin-bottom:8px;
    border-radius:8px;
    text-decoration:none;
    color:#555;
}

.sidebar a:hover,
.sidebar a.active{
    background:#20c997;
    color:white;
} */

    .sidebar {
        width: 260px;
        min-height: 100vh;
        background: linear-gradient(180deg, #0f172a, #1e3a8a);
        color: white;
        padding: 20px 15px;
    }

    /* LOGO */
    .sidebar .logo {
        text-align: center;
        margin-bottom: 25px;
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
    .sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        color: #e2e8f0;
        text-decoration: none;
        margin-bottom: 8px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar a.active {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        color: white;
    }

    /* ICON */
    .sidebar i {
        font-size: 18px;
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

    .sidebar {
        width: 260px;
        height: 100vh;
        /* penting */
        position: fixed;
        /* biar full dan nempel */
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #0f172a, #1e3a8a);
        color: white;
        padding: 20px 15px;
        overflow-y: auto;
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

    /* BADGE */
    .badge-aktif {
        background: #d1fae5;
        color: #065f46;
        padding: 6px 10px;
        border-radius: 20px;
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
                                Keseluruhan barang
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

            <!-- SEARCH -->
            <!-- <div class="input-group" style="width:220px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>

                <input 
                    type="text" 
                    id="search"
                    class="form-control border-start-0"
                    placeholder="Cari..."
                >
            </div> -->

            <!-- KATEGORI -->
            <select id="kategori" class="form-select" style="width:170px;">
                <option value="">Kategori</option>
                <option>ATK</option>
                <option>Elektronik</option>
                <option>Persediaan</option>
                <option>Peralatan</option>
            </select>

            <!-- TOMBOL -->
            <button 
                class="btn btn-success px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah"
            >
                + Tambah
            </button>

        </div>

    </div>

    <!-- TABLE -->
    <table id="tableBarang" class="table align-middle">

        <thead>
            <tr>
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

                        <select name="kategori" id="edit_kategori" class="form-control">
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
    <footer class="footer-custom">
        <div class="footer-inner">
            <div class="footer-content">

                <div class="footer-left d-flex align-items-center gap-3">
                    <img src="logo.png" width="60">
                    <b>KANTOR IMIGRASI KELAS I TPI SAMARINDA</b>
                </div>

                <div>
                    <p><i class="bi bi-geo-alt"></i> Samarinda</p>
                    <p><i class="bi bi-telephone"></i> 0811-5565-000</p>
                    <p><i class="bi bi-envelope"></i> kanim_samarinda@imigrasi.go.id</p>
                </div>

            </div>

            <div class="text-center mt-3">
                © 2026 Muhammad Farhan
            </div>
        </div>
    </footer>



   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {

    $('#tableBarang').DataTable({

        pageLength: 10,

        dom:
            '<"d-flex justify-content-between align-items-center flex-wrap mb-3"lfB>rtip',

        // buttons: [
        //     {
        //         text: 'DISPATCH SELECTED',
        //         className: 'btn-primary'
        //     }
        // ],

        language: {

            search: "",

            searchPlaceholder: "Cari barang...",

            lengthMenu: "Show _MENU_",

            paginate: {
                previous: "‹",
                next: "›"
            }

        }

    });

});
</script>

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
    function filter() {
        let key = document.getElementById("search").value.toLowerCase();
        let kat = document.getElementById("kategori").value.toLowerCase();
        let rows = document.querySelectorAll("#table-data tr");

        rows.forEach(r => {
            let text = r.innerText.toLowerCase();
            let k = r.children[3].innerText.toLowerCase();
            r.style.display = (text.includes(key) && (kat == "" || k.includes(kat))) ? "" : "none";
        });
    }
    search.onkeyup = filter;
    kategori.onchange = filter;
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



</body>

</html>