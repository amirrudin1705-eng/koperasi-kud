<?php
session_start();

require_once '../config/config.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Ambil user
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user  = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<script>
        alert('Username tidak ditemukan!');
        window.location='login.php';
    </script>";
    exit;
}

// Verifikasi password
if (!password_verify($password, $user['password'])) {
    echo "<script>
        alert('Password salah!');
        window.location='login.php';
    </script>";
    exit;
}

// Regenerasi session
session_regenerate_id(true);

// Set session
$_SESSION['login']   = true;
$_SESSION['id_user'] = $user['id_user'];
$_SESSION['nama']    = $user['nama'];
$_SESSION['role']    = $user['role'];

// Redirect berdasarkan role
switch ($user['role']) {
    case 'admin':
        header("Location: ../admin/dashboard.php");
        break;

    case 'staf':
        header("Location: ../staf_keuangan/dashboard.php");
        break;

    case 'anggota':
        header("Location: ../anggota/dashboard.php");
        break;

    default:
        session_destroy();
        header("Location: login.php");
        break;
}

exit;
