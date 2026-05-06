<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

$query = mysqli_query($conn,"
SELECT b.nama_barang, SUM(p.jumlah) as total_keluar
FROM peminjaman p
JOIN barang b ON p.barang_id = b.id
WHERE p.status='disetujui'
AND DATE_FORMAT(p.created_at, '%Y-%m') = '$bulan'
GROUP BY p.barang_id
");

$total_semua = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background: #f1f5f9;
    }

    /* NAVBAR */
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

    /* WRAPPER */
    .wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* CONTENT */
    .content {
        margin-left: 260px;
        margin-top: 80px;
        padding: 20px;
    }

   .content{
    margin-left:260px;   /* WAJIB ADA */
    margin-top:80px;
    padding:20px;
    width:calc(100% - 260px); /* BIAR FULL */
    max-width:100%; /* HAPUS BATAS */
}

.content{
    margin-left:260px;
    margin-top:80px;
    padding:20px 40px;
    width:calc(100% - 260px);
}

.card-box{
    max-width:100%;
}

    /* CARD */
    .card-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* FOOTER */
    .footer-custom {
        margin-left: 260px;
        background: #334155;
        color: white;
        padding: 20px;
        text-align: center;
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
                <img src="logo.png">
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
                <i class="bi bi-gear"></i> Pengaturan
            </a>

            <a href="../auth/logout.php" class="text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>

            <!-- FOOT SIDEBAR -->
            <div class="sidebar-footer mt-4">
                <img src="logo.png">
                <div><b>SIMBAK</b></div>
                <small>Sistem inventaris terintegrasi</small>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="container-fluid">

                <div class="card-box">
                    <h5>Rekap Barang Keluar</h5>

                    <form method="GET" class="d-flex gap-2">
                        <input type="month" name="bulan" class="form-control" value="<?= $bulan ?>"
                            style="max-width:200px;">

                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>

                        <a href="export_pdf.php?bulan=<?= $bulan ?>" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    </form>
                </div>

                <div class="card-box">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Total Keluar</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $no = 1;

                                if(mysqli_num_rows($query) > 0){
                                while($d = mysqli_fetch_assoc($query)){
                                $total_semua += $d['total_keluar'];
                                ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $d['nama_barang'] ?></td>
                                <td><?= $d['total_keluar'] ?></td>
                            </tr>
                            <?php }} else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <h5>Total Keluar: <b><?= $total_semua ?></b></h5>

                </div>

            </div>
    </div>
</div>
            <!-- FOOTER -->
            <div class="footer-custom">
                © 2026 SIMBAK - Sistem Inventaris
            </div>

</body>

</html>