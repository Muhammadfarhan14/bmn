<!-- <?php include '../config/koneksi.php'; ?>

<form method="POST">
Nama: <input type="text" name="nama"><br>
Jumlah: <input type="number" name="jumlah"><br>
Lokasi: <input type="text" name="lokasi"><br>

<button name="simpan">Simpan</button>
</form>

<?php
if(isset($_POST['simpan'])){
    mysqli_query($conn,"INSERT INTO barang VALUES('','$_POST[nama]','$_POST[jumlah]','$_POST[lokasi]','baik')");
    header("Location: barang.php");
}
?> -->