<?php
session_start();
require_once '../config/mail.php';

$email = $_POST['email'];

$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_expired'] = time() + 300; // 5 menit

sendEmail(
  $email,
  'Kode OTP Registrasi',
  "<p>Kode OTP Anda:</p><h2>$otp</h2><p>Berlaku 5 menit.</p>"
);

echo "OTP telah dikirim ke email Anda.";
