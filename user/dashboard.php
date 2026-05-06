<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

// hanya user boleh masuk
if($_SESSION['role'] != 'user'){
    header("Location: ../auth/login.php");
    exit;
}

/* =======================
   PROSES PINJAM
======================= */
if(isset($_POST['pinjam'])){
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan']; // 🔥 TAMBAHAN
    $user_id = $_SESSION['id'];

    mysqli_query($conn,"INSERT INTO peminjaman 
    (user_id, barang_id, jumlah, catatan, status) 
    VALUES 
    ('$user_id','$barang_id','$jumlah','$catatan','pending')");

    echo "<script>alert('Pengajuan berhasil'); location='dashboard.php';</script>";
}
?>

<?php
/* =======================
   PROSES CATATAN USER
======================= */
if(isset($_POST['kirim_catatan'])){
    $catatan = $_POST['catatan'];
    $user_id = $_SESSION['id'];

    mysqli_query($conn,"INSERT INTO catatan_user (user_id, isi_catatan)
    VALUES ('$user_id','$catatan')");

    echo "<script>alert('Catatan berhasil dikirim'); location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background: #f5f6fa;
        overflow-x: hidden;
    }

    .card-box {
        border-radius: 12px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .status-ada {
        background: #d4edda;
        color: #155724;
    }

    .status-habis {
        background: #f8d7da;
        color: #721c24;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .input-group,
    .form-select {
        border-radius: 8px;
    }

    @media (max-width: 768px) {

        .card-box {
            padding: 15px;
        }

        /* Table scroll */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            min-width: 600px;
        }

        /* Supaya tombol rapi */
        .btn {
            width: 100%;
        }

        /* Badge tetap kecil */
        .badge-status {
            font-size: 11px;
            padding: 5px 8px;
        }
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

    .content {
        margin-left: 260px;
        padding: 20px;
        margin-top: 80px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container-custom {
        max-width: 1200px;
        margin: auto;
        padding: 0 15px;
    }

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

    .main {
        width: 100%;
        margin: 0;
    }

    .main {
        flex: 1;
        width: 100%;
    }

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

    html,
    body {
        height: 100%;
    }

    .wrapper {
        min-height: 100%;
        display: flex;
    }

    .content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-box {
        width: 100%;
    }


    /* RESPONSIVE */
    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
        }

        .footer-left {
            justify-content: center;
        }
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
    <div class="wrapper" style="min-height:100vh; display:flex;">

        <!-- SIDEBAR -->
        <div class="sidebar d-flex flex-column">
            <div class="logo">
                <img src="polos.png">
                <h5>SIMBAK</h5>
                <small>Sistem Inventaris Barang Masuk & Keluar</small>
            </div>

            <a href="dashboard.php" class="active">
                <i class="bi bi-house-door"></i> Dashboard
            </a>

            <a href="../auth/logout.php" class="text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>

            <!-- <div class="sidebar-footer mt-4">
                <img src="simbak.jpeg">
                <div><b>SIMBAK</b></div>
                <small>Sistem inventaris terintegrasi</small>
            </div> -->
        </div>

        <!-- ✅ TAMBAHAN CONTENT (INI YANG KURANG) -->
        <div class="content">

            <div class="main">

                <!-- HEADER -->
                <div class="card-box mb-3">
                    <div class="row align-items-center gy-2">

                        <div class="col-12 col-md-4">
                            <h5 class="mb-0"><b>Daftar Barang</b></h5>
                            <small class="text-muted">Sistem Inventaris Kantor Imigrasi</small>
                        </div>

                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">

                                <select id="kategori" class="form-select w-100">
                                    <option value="">Kategori</option>
                                    <option>Elektronik</option>
                                    <option>ATK</option>
                                    <option>Persediaan</option>
                                    <option>Peralatan</option>
                                </select>

                                <div class="input-group w-100">
                                    <span class="input-group-text">🔍</span>
                                    <input type="text" id="search" class="form-control" placeholder="Cari barang...">
                                </div>

                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCatatan">
                                    📝 Catatan
                                </button>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                            $data = mysqli_query($conn, "SELECT * FROM barang");
                            while($d = mysqli_fetch_assoc($data)){
                            ?>
                                <tr>
                                    <td><?= $d['nama_barang']; ?></td>
                                    <td><?= $d['jumlah']; ?></td>
                                    <td><?= $d['kategori']; ?></td>

                                    <td>
                                        <?php if($d['jumlah'] > 0){ ?>
                                        <span class="badge-status status-ada">Tersedia</span>
                                        <?php } else { ?>
                                        <span class="badge-status status-habis">Habis</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php if($d['jumlah'] > 0){ ?>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#pinjam<?= $d['id']; ?>">
                                            Pinjam
                                        </button>
                                        <?php } else { ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Habis</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- main -->
        </div> <!-- content -->

    </div> <!-- wrapper -->

    <!-- MODAL PINJAM -->

     
    <?php
$data = mysqli_query($conn, "SELECT * FROM barang");
while($d = mysqli_fetch_assoc($data)){
?>

<div class="modal fade" id="pinjam<?= $d['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Pinjam Barang</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="barang_id" value="<?= $d['id']; ?>">

                    <p><b><?= $d['nama_barang']; ?></b></p>

                    <div class="mb-2">
                        <label>Jumlah Pinjam</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="pinjam" class="btn btn-success">Ajukan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php } ?>

    <div class="modal fade" id="modalCatatan">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Tambah Catatan</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST">
                    <div class="modal-body">

                        <!-- FIX -->
                        <textarea name="catatan" class="form-control" placeholder="Masukkan catatan..."
                            required></textarea>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-success" name="kirim_catatan">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<footer class="footer-custom">
        <div class="container-custom">
            <div class="footer-content">

                <!-- KIRI -->
                <div class="footer-left">
                    <img src="logo.png">
                    <div>
                        <b>KANTOR IMIGRASI KELAS I TPI SAMARINDA</b>
                    </div>
                </div>

                <!-- KANAN -->
                <div class="footer-right">
                    <p><i class="bi bi-geo-alt"></i> Jl. Ir. H. Juanda No.45, Samarinda</p>
                    <p><i class="bi bi-telephone"></i> 0811-5565-000</p>
                    <p><i class="bi bi-envelope"></i> kanim_samarinda@imigrasi.go.id</p>
                </div>

            </div>

            <div class="footer-bottom">
                © 2026 Dibuat Oleh Muhammad Farhan
            </div>
        </div>
    </footer>
   

    <script>
    document.getElementById("kategori").addEventListener("change", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let kategoriCell = row.children[0].innerText
                .toLowerCase(); // nanti kita ambil dari kolom kategori
            let fullText = row.innerText.toLowerCase();

            if (value === "") {
                row.style.display = "";
            } else {
                row.style.display = fullText.includes(value) ? "" : "none";
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

</body>

 

</html>