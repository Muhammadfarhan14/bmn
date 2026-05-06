<?php

/* =========================
   SESSION CONFIG
========================= */
session_set_cookie_params([
    'lifetime' => 86400, // 24 jam
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

include '../config/koneksi.php';

/* =========================
   AMBIL INPUT
========================= */
$username = mysqli_real_escape_string(
    $conn,
    $_POST['username']
);

$password = mysqli_real_escape_string(
    $conn,
    $_POST['password']
);

/* =========================
   CEK USER
========================= */
$query = mysqli_query(
    $conn,
    "SELECT * FROM users 
     WHERE username='$username'"
);

$user = mysqli_fetch_assoc($query);

/* =========================
   VALIDASI USER
========================= */
if($user){

    // PASSWORD VALID
    if($password == $user['password']){

        // BUAT SESSION LEBIH STABIL
        session_regenerate_id(true);

        /* =========================
           SIMPAN SESSION
        ========================= */
        $_SESSION['id'] = $user['id'];

        $_SESSION['username'] =
        $user['username'];

        $_SESSION['role'] =
        $user['role'];

        $_SESSION['ruangan'] =
        $user['ruangan'];

        /* =========================
           REDIRECT ROLE
        ========================= */
        if($user['role'] == 'admin'){

            header(
                "Location: ../admin/dashboard.php"
            );

        } else {

            header(
                "Location: ../user/dashboard.php"
            );

        }

        exit;

    } else {

        echo "
        <script>
            alert('Password salah');
            window.location='login.php';
        </script>
        ";

    }

} else {

    echo "
    <script>
        alert('User tidak ditemukan');
        window.location='login.php';
    </script>
    ";

}

?>