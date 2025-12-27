<?php
session_start();
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi Anggota | <?= APP_NAME ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>

<div class="login-container">
<div class="login-box">

<div class="text-center mb-4">
<i class="bi bi-person-plus fs-1 text-primary"></i>
<h4 class="fw-bold">Registrasi Anggota</h4>
<p class="text-muted">Verifikasi email dengan OTP</p>
</div>

<form action="register_process.php" method="post">

<div class="mb-3">
<label>Nama Lengkap</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<!-- EMAIL + OTP -->
<div class="mb-3">
<label>Email</label>
<div class="input-group">
<input type="email" name="email" id="email" class="form-control" required>
<button type="button" class="btn btn-outline-primary" id="sendOtpBtn">Kirim OTP</button>
</div>
</div>

<div class="mb-3 d-none" id="otpSection">
<label>Kode OTP</label>
<div class="input-group">
<input type="text" name="otp" class="form-control" placeholder="6 digit OTP">
<button type="button" class="btn btn-outline-secondary" id="resendBtn" disabled>
Resend (<span id="timer">60</span>s)
</button>
</div>
<small class="text-muted">OTP dikirim ke email</small>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
<label>Konfirmasi Password</label>
<input type="password" name="konfirmasi_password" class="form-control" required>
</div>

<button class="btn btn-primary-custom w-100 mt-3">Daftar</button>

</form>

<div class="text-center mt-3">
<a href="login.php" class="small text-muted">Sudah punya akun? Login</a>
</div>

</div>
</div>

<script>
let timer = 60;
let interval;

function startTimer() {
  timer = 60;
  document.getElementById('resendBtn').disabled = true;
  interval = setInterval(() => {
    timer--;
    document.getElementById('timer').innerText = timer;
    if (timer <= 0) {
      clearInterval(interval);
      document.getElementById('resendBtn').disabled = false;
      document.getElementById('resendBtn').innerText = 'Resend OTP';
    }
  }, 1000);
}

document.getElementById('sendOtpBtn').onclick = () => {
  const email = document.getElementById('email').value;
  if (!email) return alert('Isi email dulu');

  fetch('send_otp.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'email=' + email
  }).then(r => r.text()).then(res => {
    alert(res);
    document.getElementById('otpSection').classList.remove('d-none');
    startTimer();
  });
};

document.getElementById('resendBtn').onclick = () => {
  document.getElementById('sendOtpBtn').click();
  document.getElementById('resendBtn').innerHTML = 'Resend (<span id="timer">60</span>s)';
};
</script>

</body>
</html>
