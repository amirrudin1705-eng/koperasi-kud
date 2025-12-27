<?php
session_start();
require_once '../config/database.php';

$nama       = $_POST['nama'];
$username   = $_POST['username'];
$email      = $_POST['email'];
$password   = $_POST['password'];
$konfirmasi = $_POST['konfirmasi_password'];
$otp        = $_POST['otp'];

if (!isset($_SESSION['otp']) || time() > $_SESSION['otp_expired']) {
    echo "<script>
        alert('OTP sudah kadaluarsa. Silakan kirim ulang OTP.');
        window.location='register.php';
    </script>";
    exit;
}

if ($otp != $_SESSION['otp']) {
    echo "<script>
        alert('OTP tidak valid!');
        window.location='register.php';
    </script>";
    exit;
}

if ($password !== $konfirmasi) {
    echo "<script>
        alert('Password dan konfirmasi tidak sama!');
        window.location='register.php';
    </script>";
    exit;
}

$cek = mysqli_query($conn, "
    SELECT id_user FROM users 
    WHERE username='$username' OR email='$email'
");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('Username atau email sudah terdaftar!');
        window.location='register.php';
    </script>";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// INSERT ke tabel users
$insertUser = mysqli_query($conn, "
    INSERT INTO users (nama, username, email, password, role)
    VALUES ('$nama', '$username', '$email', '$hash', 'anggota')
");

if (!$insertUser) {
    die(mysqli_error($conn));
}

// AMBIL id_user BARU (INI WAJIB)
$id_user_baru = mysqli_insert_id($conn);

// INSERT ke tabel anggota
$insertAnggota = mysqli_query($conn, "
    INSERT INTO anggota (id_user, status_keanggotaan)
    VALUES ('$id_user_baru', 'aktif')
");

if (!$insertAnggota) {
    die(mysqli_error($conn));
}


unset($_SESSION['otp'], $_SESSION['otp_expired']);

echo "<script>
    alert('Pendaftaran berhasil! Silakan login.');
    window.location='login.php';
</script>";
exit;
