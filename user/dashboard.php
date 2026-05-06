<?php
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// session_start();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
    body {
        background: #f5f6fa;
        overflow-x: hidden;
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

        .dataTables_wrapper .row{
    align-items:center;
}
.card-box{
    border-radius:20px;
}

.btn-warning{
    border:none;
    font-weight:600;
}

.btn-warning:hover{
    transform:translateY(-2px);
    transition:0.2s;
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

    /* .navbar-custom {
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
    } */

    .main {
        width: 100%;
        margin: 0;
    }

    .main {
        flex: 1;
        width: 100%;
    }

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

    /* DATATABLE */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:15px;
}

.dataTables_wrapper .dataTables_filter input{
    border-radius:10px;
    border:1px solid #dbeafe;
    padding:8px 12px;
}

.dataTables_wrapper .dataTables_length select{
    border-radius:10px;
    border:1px solid #dbeafe;
    padding:5px 10px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{
    border-radius:10px !important;
    margin:0 3px;
}

table.dataTable thead th{
    background:#f8fafc;
    color:#475569;
    font-weight:600;
}

.table tbody tr:hover{
    background:#f1f5f9;
    transition:0.2s;
}

.table td,
.table th{
    vertical-align:middle;
}

/* DATATABLE HEADER */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:20px;
}

/* SHOW DATA */
.dataTables_length label{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:500;
    color:#475569;
}

/* SELECT */
.dataTables_length select{
    min-width:80px !important;
    border-radius:12px !important;
    border:1px solid #cbd5e1 !important;
    padding:8px 35px 8px 12px !important;
    background-color:white !important;
}

/* SEARCH */
.dataTables_filter label{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:500;
}

.dataTables_filter input{
    width:220px !important;
    border-radius:12px !important;
    border:1px solid #cbd5e1 !important;
    padding:10px 14px !important;
}

/* PAGINATION */
.dataTables_paginate{
    margin-top:20px !important;
}

.paginate_button{
    border-radius:10px !important;
    margin:0 4px !important;
}

/* INFO TEXT */
.dataTables_info{
    padding-top:18px !important;
    color:#64748b;
}

/* TABLE */
table.dataTable{
    border-collapse:separate !important;
    border-spacing:0;
}

/* ===== TOP DATATABLE ===== */
.dataTables_wrapper .row:first-child{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

/* KIRI */
.dataTables_length{
    float:left;
}

/* KANAN */
.dataTables_filter{
    float:right;
    text-align:right;
}

/* LABEL SEARCH */
.dataTables_filter label{
    display:flex;
    align-items:center;
    gap:12px;
    justify-content:flex-end;
    font-weight:500;
}

/* INPUT SEARCH */
.dataTables_filter input{

    width:240px !important;

    border-radius:14px !important;

    border:1px solid #dbeafe !important;

    padding:10px 16px !important;

    margin-left:10px !important;

    transition:0.3s ease;

    background:white !important;
}

/* SAAT DIKLIK */
.dataTables_filter input:focus{

    border:1px solid #3b82f6 !important;

    box-shadow:
    0 0 0 4px rgba(59,130,246,0.15) !important;

    outline:none !important;
}

/* SELECT SHOW DATA */
.dataTables_length select{

    margin:0 10px !important;

    border-radius:12px !important;

    padding:8px 30px 8px 12px !important;

    border:1px solid #dbeafe !important;

    transition:0.3s;
}

.dataTables_length select:focus{

    border:1px solid #3b82f6 !important;

    box-shadow:
    0 0 0 4px rgba(59,130,246,0.15) !important;

    outline:none !important;
}

@media(max-width:768px){

    .dataTables_wrapper .row:first-child{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .dataTables_filter{
        width:100%;
    }

    .dataTables_filter label{
        width:100%;
        justify-content:space-between;
    }

    .dataTables_filter input{
        width:100% !important;
    }

}

    </style>
</head>

<body>
    <!-- NAVBAR -->
    <div class="navbar-custom">

    <!-- LEFT -->
    <div class="navbar-left">

        <h5>Selamat Datang 👋</h5>


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

                <!-- TABLE -->
                <div class="card-box shadow-sm border-0">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <!-- KIRI -->
    <div>
        <h4 class="fw-bold mb-1">
            Daftar Barang
        </h4>

        <small class="text-muted">
            Sistem Inventaris Kantor Imigrasi
        </small>
    </div>

    <!-- KANAN -->
    <button 
        class="btn btn-warning px-4 shadow-sm"
        data-bs-toggle="modal"
        data-bs-target="#modalCatatan"
    >

        <i class="bi bi-pencil-square"></i>
        Catatan

    </button>

</div>
                    <div class="table-responsive">
                        <table id="tableBarang" class="table table-hover align-middle">
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

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){

    $('#tableBarang').DataTable({

        responsive:true,

        pageLength:50,

        lengthMenu:[
            [10,25,50,100],
            [10,25,50,100]
        ],

        language:{
            search:"Cari:",
            lengthMenu:"Tampilkan _MENU_ data",
            zeroRecords:"Data tidak ditemukan",
            info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate:{
                previous:"←",
                next:"→"
            }
        }

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

 

</html>