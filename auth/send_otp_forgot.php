<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail.php';

$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';

$cek = mysqli_query($conn, "
    SELECT id_user FROM users 
    WHERE username='$username' AND email='$email'
");

if (mysqli_num_rows($cek) === 0) {
        echo 'INVALID';
    exit;
}

$otp = rand(100000, 999999);
$_SESSION['otp_reset'] = $otp;
$_SESSION['otp_reset_expired'] = time() + 300;
$_SESSION['reset_user'] = $username;

sendEmail(
    $email,
    'Kode OTP Reset Password',
    "<h3>Kode OTP Anda: $otp</h3><p>Berlaku 5 menit.</p>"
);

echo 'VALID';
