<?php
require_once '../config/config.php';

session_unset();
session_destroy();

if (isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | <?= APP_NAME ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>

<div class="login-container">
  <div class="login-box">

    
    <div class="text-center mb-4">
      <i class="bi bi-bank fs-1 text-primary"></i>
      <h4 class="mt-2 fw-bold"><?= APP_NAME ?></h4>
      <p class="text-muted">Silakan login untuk melanjutkan</p>
    </div>

    
    <form action="login_process.php" method="post">

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
      </div>

      <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary-custom">
        Login
        </button>
      </div>

    </form>

    <div class="mb-2 text-end">
      <a href="forgot_password.php" class="text-muted small">
        Lupa password?
      </a>
    </div>

    <div class="text-center mt-4">
      <a href="register.php">
         Daftar sebagai Anggota
      </a>
    </div>

    <div class="text-center mt-4 text-muted small">
      &copy; <?= date('Y') ?> KUD Simpan Pinjam
    </div>

  </div>
</div>

</body>
</html>
