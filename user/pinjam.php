<?php
session_start();
include '../config/koneksi.php';
?>

<form method="POST">
    Barang:
    <select name="barang_id">
        <?php
$data = mysqli_query($conn,"SELECT * FROM barang");
while($d=mysqli_fetch_assoc($data)){
    echo "<option value='$d[id]'>$d[nama_barang]</option>";
}
?>
    </select>

    Jumlah: <input type="number" name="jumlah">
    <button name="pinjam">Pinjam</button>
</form>

<?php
if(isset($_POST['pinjam'])){
    mysqli_query($conn,"INSERT INTO peminjaman VALUES('','$_SESSION[id]','$_POST[barang_id]','$_POST[jumlah]',NOW(),'pending')");
    echo "Berhasil diajukan";
}
?>