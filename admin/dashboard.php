<?php
session_start();
include '../config/koneksi.php';

/* NOTIFIKASI */
$data_pinjam = mysqli_query($conn,"
SELECT p.*, b.nama_barang, u.username 
FROM peminjaman p
JOIN barang b ON p.barang_id = b.id
JOIN users u ON p.user_id = u.id
WHERE p.status='pending'
");
$notif = mysqli_query($conn,"SELECT * FROM peminjaman WHERE status='pending'");
$total = mysqli_num_rows($notif);

/* VALIDASI */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

/* AKSI PINJAM */
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
    $kode   = $_POST['kode_barang'];
    $nama   = $_POST['nama'];
    $jumlah = $_POST['jumlah'];
    $kategori = $_POST['kategori'];

    $status = ($jumlah > 0) ? 'Tersedia' : 'Habis';

    mysqli_query($conn,"INSERT INTO barang 
    (kode_barang, kategori, status, nama_barang, jumlah, lokasi, kondisi) 
    VALUES ('$kode','$kategori','$status','$nama','$jumlah','Gudang','baik')");

    header("Location: dashboard.php");
    exit;
}

/* UPDATE */
if(isset($_POST['update'])){
    $id     = $_POST['id'];
    $kode   = $_POST['kode_barang'];
    $nama   = $_POST['nama'];
    $jumlah = $_POST['jumlah'];

    $status = ($jumlah > 0) ? 'Tersedia' : 'Habis';

    mysqli_query($conn,"UPDATE barang SET
        kode_barang='$kode',
        status='$status',
        nama_barang='$nama',
        jumlah='$jumlah'
        WHERE id='$id'
    ");

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background: #f1f5f9;
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

    .card-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .badge-aktif {
        background: #d1fae5;
        color: #065f46;
        padding: 6px 10px;
        border-radius: 20px;
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

<body>

    <!-- NAVBAR -->
    <div class="navbar-custom">
        <div class="d-flex align-items-center gap-2">
            <img src="logo.png" width="40">
            <b>KANTOR IMIGRASI SAMARINDA</b>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?></span>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="main">

    <div class="card-box">
    <h5 class="mb-3">Permintaan Peminjaman</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php while($p = mysqli_fetch_assoc($data_pinjam)){ ?>
                <tr>
                    <td><?= $p['nama_barang']; ?></td>
                    <td><?= $p['jumlah']; ?></td>
                    <td><?= $p['user_id']; ?></td>

                    <td>
                        <span class="badge bg-warning">Pending</span>
                    </td>

                    <td>
                        <a href="?id=<?= $p['id']; ?>&aksi=terima" 
                           class="btn btn-success btn-sm">
                           ✔
                        </a>

                        <a href="?id=<?= $p['id']; ?>&aksi=tolak" 
                           class="btn btn-danger btn-sm">
                           ✖
                        </a>
                    </td>
                </tr>
                <?php } ?>

                <?php if(mysqli_num_rows($data_pinjam) == 0){ ?>
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
        <div class="card-box">
            <div class="row align-items-center">

                <!-- KIRI -->
                <div class="col-md-4">
                    <h5 class="mb-0">Dashboard Admin</h5>
                    <small class="text-muted">Sistem Inventaris</small>
                </div>

                <!-- KANAN -->
                <div class="col-md-8 mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-md-end align-items-center gap-2">

                        <!-- SEARCH -->
                       <div class="input-group" style="max-width:200px;">
    <span class="input-group-text">
        <i class="bi bi-search"></i>
    </span>
    <input type="text" id="search" class="form-control" placeholder="Cari...">
</div>
                            

                        <!-- KATEGORI -->
                        <select id="kategori" class="form-select" style="max-width:150px;">
                            <option value="">Kategori</option>
                            <option>ATK</option>
                            <option>Elektronik</option>
                            <option>Persediaan</option>
                            <option>Peralatan</option>
                        </select>

                        <!-- BUTTON TAMBAH (FIX SEJAJAR) -->
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            + Tambah Barang
                        </button>

                    </div>
                </div>

            </div>
        </div>
        <!-- DATA -->
        <div class="card-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Status</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="table-data">
                    <?php
$data = mysqli_query($conn,"SELECT * FROM barang");
while($d=mysqli_fetch_assoc($data)){
?>
                    <tr>
                        <td><?= $d['kode_barang']; ?></td>

                        <td>
                            <?= ($d['jumlah']>0) 
? '<span class="badge-aktif">Tersedia</span>' 
: '<span class="badge bg-danger">Habis</span>' ?>
                        </td>

                        <td><?= $d['nama_barang']; ?></td>
                        <td><?= $d['kategori']; ?></td>
                        <td><?= $d['jumlah']; ?></td>

                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="editData('<?= $d['id'] ?>','<?= $d['kode_barang'] ?>','<?= $d['nama_barang'] ?>','<?= $d['jumlah'] ?>')">
                                ✏
                            </button>

                            <a href="hapus_barang.php?id=<?= $d['id'] ?>" class="btn btn-danger btn-sm">
                                🗑
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- MODAL TAMBAH -->
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
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="text" name="kode_barang" id="edit_kode" class="form-control mb-2">
                        <input type="text" name="nama" id="edit_nama" class="form-control mb-2">
                        <input type="number" name="jumlah" id="edit_jumlah" class="form-control mb-2">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function editData(id, kode, nama, jumlah) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_kode').value = kode;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jumlah').value = jumlah;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
    </script>

    <script>
document.getElementById("kategori").addEventListener("change", function(){
    let kategori = this.value.toLowerCase();
    let rows = document.querySelectorAll("#table-data tr");

    rows.forEach(row => {
        let kategoriKolom = row.children[3].innerText.toLowerCase();

        if(kategori === ""){
            row.style.display = "";
        } else {
            row.style.display = kategoriKolom.includes(kategori) ? "" : "none";
        }
    });
});
</script>

<script>
function filterData(){
    let keyword = document.getElementById("search").value.toLowerCase();
    let kategori = document.getElementById("kategori").value.toLowerCase();
    let rows = document.querySelectorAll("#table-data tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        let kategoriKolom = row.children[3].innerText.toLowerCase();

        let cocokSearch = text.includes(keyword);
        let cocokKategori = kategori === "" || kategoriKolom.includes(kategori);

        row.style.display = (cocokSearch && cocokKategori) ? "" : "none";
    });
}

document.getElementById("search").addEventListener("keyup", filterData);
document.getElementById("kategori").addEventListener("change", filterData);
</script>
</body>

</html>