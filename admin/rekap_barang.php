<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'bulan';

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

if($filter == 'tahun'){

    $query = mysqli_query($conn,"
    SELECT 
        b.nama_barang,
        SUM(p.jumlah) as total_keluar
    FROM peminjaman p
    JOIN barang b ON p.barang_id = b.id
    WHERE p.status='disetujui'
    AND YEAR(p.created_at) = '$tahun'
    GROUP BY p.barang_id
    ");

} else {

    $query = mysqli_query($conn,"
    SELECT 
        b.nama_barang,
        SUM(p.jumlah) as total_keluar
    FROM peminjaman p
    JOIN barang b ON p.barang_id = b.id
    WHERE p.status='disetujui'
    AND DATE_FORMAT(p.created_at, '%Y-%m') = '$bulan'
    GROUP BY p.barang_id
    ");

}

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

    .custom-table{
    border-radius:15px;
    overflow:hidden;
}

.custom-table thead{
    background:#1e293b;
    color:white;
}

.custom-table thead th{
    padding:16px;
    border:none;
}

.custom-table tbody tr{
    transition:0.2s;
}

.custom-table tbody tr:hover{
    background:#eff6ff;
    transform:scale(1.01);
}

.custom-table td{
    padding:16px;
}

.card-box{
    animation:fadeIn 0.5s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(15px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

    </style>
</head>

<body>

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
                    <a href="dashboard.php">
    <i class="bi bi-grid-1x2-fill"></i>
    <span>Dashboard</span>
</a>

<a href="rekap_barang.php" class="active">
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
                <img src="logo.png">
                <div><b>SIMBAK</b></div>
                <small>Sistem inventaris terintegrasi</small>
            </div> -->

        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="container-fluid">

                <div class="card-box">
                   <div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h4 class="fw-bold mb-0">
            Rekap Barang Keluar
        </h4>

       <small class="text-muted">

<?php if($filter == 'bulan'){ ?>

    Rekap barang keluar bulan 
    <b><?= date('F Y', strtotime($bulan)) ?></b>

<?php } else { ?>

    Rekap barang keluar tahun 
    <b><?= $tahun ?></b>

<?php } ?>

</small>
    </div>

    <!-- <i class="bi bi-bar-chart-line-fill text-primary fs-2"></i> -->

</div>

                   <form method="GET" class="row g-3 align-items-end">

    <!-- FILTER -->
    <div class="col-md-3">

        <label class="form-label fw-semibold">
            Jenis Rekap
        </label>

        <select name="filter" class="form-select" onchange="this.form.submit()">

            <option value="bulan" <?= $filter == 'bulan' ? 'selected' : '' ?>>
                Rekap Bulanan
            </option>

            <option value="tahun" <?= $filter == 'tahun' ? 'selected' : '' ?>>
                Rekap Tahunan
            </option>

        </select>

    </div>

    <!-- BULAN -->
    <?php if($filter == 'bulan'){ ?>

    <div class="col-md-3">

        <label class="form-label fw-semibold">
            Pilih Bulan
        </label>

        <input 
            type="month"
            name="bulan"
            class="form-control"
            value="<?= $bulan ?>"
        >

    </div>

    <?php } else { ?>

    <!-- TAHUN -->
    <div class="col-md-3">

        <label class="form-label fw-semibold">
            Pilih Tahun
        </label>

        <select name="tahun" class="form-select">

            <?php
            for($i=date('Y'); $i>=2020; $i--){
            ?>

            <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>>
                <?= $i ?>
            </option>

            <?php } ?>

        </select>

    </div>

    <?php } ?>

    <!-- BUTTON -->
    <div class="col-md-auto">

        <button class="btn btn-primary px-4">
            <i class="bi bi-search"></i>
            Tampilkan
        </button>

    </div>

    <div class="col-md-auto">

        <a 
            href="export_pdf.php?filter=<?= $filter ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
            class="btn btn-danger px-4"
        >

            <i class="bi bi-file-earmark-pdf"></i>
            PDF

        </a>

    </div>

</form>
                </div>

                <div class="card-box">

                    <table class="table custom-table align-middle">
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