<?php include '../config/koneksi.php'; ?>

<h2>Konfirmasi Peminjaman</h2>

<table border="1">
<tr>
<th>User</th>
<th>Barang</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn,"
SELECT p.*, b.nama_barang 
FROM peminjaman p 
JOIN barang b ON p.barang_id=b.id
");

while($d=mysqli_fetch_assoc($data)){
?>
<tr>
<td><?= $d['user_id'] ?></td>
<td><?= $d['nama_barang'] ?></td>
<td><?= $d['status'] ?></td>
<td>
<a href="?id=<?= $d['id'] ?>&status=disetujui">Setujui</a>
<a href="?id=<?= $d['id'] ?>&status=ditolak">Tolak</a>
</td>
</tr>
<?php } ?>

</table>

<?php
if(isset($_GET['id'])){
    mysqli_query($conn,"UPDATE peminjaman SET status='$_GET[status]' WHERE id='$_GET[id]'");
    header("Location: konfirmasi.php");
}
?>