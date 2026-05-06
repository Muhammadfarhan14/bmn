<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

include '../config/koneksi.php';

$bulan = $_GET['bulan'];

$query = mysqli_query($conn,"
SELECT b.nama_barang, SUM(p.jumlah) as total_keluar
FROM peminjaman p
JOIN barang b ON p.barang_id = b.id
WHERE p.status='disetujui'
AND DATE_FORMAT(p.created_at, '%Y-%m') = '$bulan'
GROUP BY p.barang_id
");

$html = "
<h3>Rekap Barang Keluar</h3>
<p>Bulan: $bulan</p>

<table border='1' width='100%' cellpadding='5'>
<tr>
<th>No</th>
<th>Nama Barang</th>
<th>Total Keluar</th>
</tr>
";

$no = 1;
$total = 0;

while($d = mysqli_fetch_assoc($query)){
    $html .= "
    <tr>
        <td>$no</td>
        <td>{$d['nama_barang']}</td>
        <td>{$d['total_keluar']}</td>
    </tr>";
    $total += $d['total_keluar'];
    $no++;
}

$html .= "
</table>

<h4>Total: $total</h4>
";

// DOMPDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("rekap_barang.pdf");