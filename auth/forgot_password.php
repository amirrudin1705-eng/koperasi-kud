<?php session_start(); require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Password | <?= APP_NAME ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>

<div class="login-container">
<div class="login-box">

<h4 class="text-center fw-bold mb-3">Lupa Password</h4>
<p class="text-center text-muted">Verifikasi akun dengan OTP</p>

<form action="reset_password_process.php" method="post">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<div class="input-group">
<input type="email" name="email" id="email" class="form-control" required>
<button type="button" class="btn btn-outline-primary" id="sendOtpBtn">Kirim OTP</button>
</div>
</div>

<div class="mb-3 d-none" id="otpSection">
<label>Kode OTP</label>
<input type="text" name="otp" class="form-control mb-2">
<small class="text-muted">OTP dikirim ke email</small>
</div>

<div class="mb-3 d-none" id="passwordSection">
<label>Password Baru</label>
<input type="password" name="password" class="form-control mb-2" required>
<label>Konfirmasi Password</label>
<input type="password" name="konfirmasi_password" class="form-control" required>
</div>

<button class="btn btn-primary-custom w-100 mt-3">Reset Password</button>

</form>

<div class="text-center mt-3">
<a href="login.php" class="small text-muted">Kembali ke Login</a>
</div>

</div>
</div>

<script>
document.getElementById('sendOtpBtn').onclick = () => {
  const email = document.getElementById('email').value;
  const username = document.querySelector('[name=username]').value;

  if (!email || !username) {
    alert('Username dan email wajib diisi!');
    return;
  }

  fetch('send_otp_forgot.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `email=${email}&username=${username}`
  })
  .then(res => res.text())
  .then(res => {

    if (res === 'INVALID') {
      alert('Username dan email tidak cocok!');
      
      
      document.getElementById('otpSection').classList.add('d-none');
      document.getElementById('passwordSection').classList.add('d-none');

      
      document.querySelector('[name=username]').focus();
      return;
    }

    if (res === 'VALID') {
      alert('OTP telah dikirim ke email Anda');

      document.getElementById('otpSection').classList.remove('d-none');
      document.getElementById('passwordSection').classList.remove('d-none');
    }

  });
};
</script>

</body>
</html>
