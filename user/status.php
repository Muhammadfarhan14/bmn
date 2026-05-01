<?php
session_start();
include '../config/koneksi.php';

$data = mysqli_query($conn,"SELECT * FROM peminjaman WHERE user_id='$_SESSION[id]'");

while($d=mysqli_fetch_assoc($data)){
    echo "Status: ".$d['status']."<br>";
}
?>