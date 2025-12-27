<?php
session_start();
require_once '../config/database.php';

$otp        = $_POST['otp'] ?? '';
$password   = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if (!isset($_SESSION['otp_reset']) || time() > $_SESSION['otp_reset_expired']) {
    echo "<script>
        alert('Kode OTP sudah kadaluarsa. Silakan kirim ulang OTP.');
        window.location='forgot_password.php';
    </script>";
    exit;
}

if ($otp != $_SESSION['otp_reset']) {
    echo "<script>
        alert('Kode OTP tidak valid!');
        window.location='forgot_password.php';
    </script>";
    exit;
}

if ($password !== $konfirmasi) {
    echo "<script>
        alert('Password dan konfirmasi password tidak sama!');
        window.location='forgot_password.php';
    </script>";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$username = $_SESSION['reset_user'];

mysqli_query($conn, "
    UPDATE users 
    SET password='$hash' 
    WHERE username='$username'
");

unset($_SESSION['otp_reset'], $_SESSION['otp_reset_expired'], $_SESSION['reset_user']);

echo "<script>
    alert('Password berhasil direset. Silakan login.');
    window.location='login.php';
</script>";
exit;
