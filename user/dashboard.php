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
    $user_id = $_SESSION['id'];

    mysqli_query($conn,"INSERT INTO peminjaman 
    (user_id, barang_id, jumlah, status) 
    VALUES 
    ('$user_id','$barang_id','$jumlah','pending')");

    echo "<script>alert('Pengajuan berhasil dikirim'); location='dashboard.php';</script>";
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

    .container-custom {
        max-width: 1200px;
        margin: auto;
        padding: 0 15px;
    }

    .navbar-custom {
        background: #334155;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .main {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
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
<div class="navbar-custom">
    <div class="d-flex align-items-center gap-2">
        <img src="logo.png" width="40">
        <b>KANTOR IMIGRASI SAMARINDA</b>
    </div>

    <div class="d-flex align-items-center gap-3">
        <span>
            <i class="bi bi-person-circle"></i>
            <?= $_SESSION['username']; ?>
        </span>

        <a href="../auth/logout.php" class="btn btn-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</div>

<body>

    <div class="main">

        <!-- HEADER -->
        <div class="card-box mb-3">
            <div class="row align-items-center gy-2">

                <!-- KIRI -->
                <div class="col-12 col-md-4">
                    <h5 class="mb-0"><b>Daftar Barang</b></h5>
                    <small class="text-muted">Sistem Inventaris Kantor Imigrasi</small>
                </div>

                <!-- KANAN -->
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
                    </div>
                </div>

            </div>
            <!-- <div>
            <h4><b>Daftar Barang</b></h4>
            <small class="text-muted">Sistem Inventaris Kantor Imigrasi</small>
        </div> -->
        </div>

        <div class="card-box">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
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

        <!-- MODAL PINJAM -->
        <?php
$data = mysqli_query($conn, "SELECT * FROM barang");
while($d = mysqli_fetch_assoc($data)){
?>

        <div class="modal fade" id="pinjam<?= $d['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Pinjam Barang</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST">
                        <div class="modal-body">

                            <input type="hidden" name="barang_id" value="<?= $d['id']; ?>">

                            <p><b><?= $d['nama_barang']; ?></b></p>

                            <div class="mb-2">
                                <label>Jumlah Pinjam</label>
                                <input type="number" name="jumlah" class="form-control" min="1"
                                    max="<?= $d['jumlah']; ?>" required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-success" name="pinjam">Ajukan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <?php } ?>
    <script>
    document.getElementById("search").addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
    </script>

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

</html>