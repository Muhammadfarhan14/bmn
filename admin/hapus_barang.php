<?php
include '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");

// redirect ke dashboard
header("Location: dashboard.php");
exit;
?>