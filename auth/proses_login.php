<?php
session_start();
include '../config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if($user){

    if($password == $user['password']){

        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['ruangan'] = $user['ruangan'];

        if($user['role'] == 'admin'){
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;

    } else {
        echo "<script>alert('Password salah');window.location='login.php';</script>";
    }

} else {
    echo "<script>alert('User tidak ditemukan');window.location='login.php';</script>";
}