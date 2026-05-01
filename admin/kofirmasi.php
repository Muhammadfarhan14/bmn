<?php
include '../config/koneksi.php';
?>

<h3>Konfirmasi Peminjaman</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Barang</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn,"
SELECT p.*, b.nama_barang 
FROM peminjaman p
JOIN barang b ON p.barang_id = b.id
");

while($d=mysqli_fetch_assoc($data)){
?>
<tr>
<td><?= $d['nama_barang']; ?></td>
<td><?= $d['status']; ?></td>
<td>
    <a href="?id=<?= $d['id']; ?>&aksi=setuju">Setujui</a>
    <a href="?id=<?= $d['id']; ?>&aksi=tolak">Tolak</a>
</td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_GET['aksi'])){
    $id = $_GET['id'];
    $status = ($_GET['aksi'] == 'setuju') ? 'disetujui' : 'ditolak';

    mysqli_query($conn,"UPDATE peminjaman SET status='$status' WHERE id='$id'");
    echo "<script>location='konfirmasi.php'</script>";
}
?>