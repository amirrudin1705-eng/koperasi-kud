<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT nama, username, email, foto
    FROM users
    WHERE id_user = '$id_user'
");

$user = mysqli_fetch_assoc($query);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

$fotoProfil = !empty($user['foto'])
    ? "../assets/uploads/profile/" . $user['foto']
    : "../assets/uploads/profile/default.png";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Saya</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex min-vh-100">

  <aside class="sidebar p-3">
    <h5 class="fw-bold text-center mb-4">KUD Simpan Pinjam</h5>

    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="profil.php">
          <i class="bi bi-person"></i> Profil Saya
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="simpanan.php">
          <i class="bi bi-wallet2"></i> Simpanan Saya
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pinjaman.php">
          <i class="bi bi-file-text"></i> Pinjaman Saya
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ajukan_pinjaman.php">
          <i class="bi bi-pencil-square"></i> Ajukan Pinjaman
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="angsuran.php">
          <i class="bi bi-clock-history"></i> Riwayat Angsuran
        </a>
      </li>
      <li class="nav-item"><a class="nav-link active" href="transaksi_barang.php"><i class="bi bi-cart"></i> Transaksi Barang</a></li>

      <hr>

      <li class="nav-item">
        <a class="nav-link text-danger" href="../auth/logout.php">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </li>
    </ul>
  </aside>

  <main class="flex-fill bg-light">

    <div class="d-flex justify-content-end align-items-center p-3 bg-white shadow-sm">
      <div class="text-end me-3">
        <div class="fw-semibold"><?= htmlspecialchars($user['nama']) ?></div>
        <small class="text-muted">Anggota</small>
      </div>
      <img src="<?= $fotoProfil ?>?v=<?= time() ?>"
           class="rounded-circle"
           width="42"
           height="42"
           style="object-fit: cover;"
           alt="Foto Profil">
    </div>

    <div class="container-fluid p-4">
      <h4 class="fw-bold mb-4">Profil Saya</h4>

      <div class="row g-4">

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <img src="<?= $fotoProfil ?>?v=<?= time() ?>" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
              
              <form action="helpers/profil_update.php" method="post" enctype="multipart/form-data">
                <input type="file" name="foto" class="form-control mb-2" accept="image/*" required>
                <button class="btn btn-primary btn-sm w-100">Ganti Foto</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Informasi Akun</h6>

              <div class="mb-2">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" disabled>
              </div>

              <div class="mb-2">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
              </div>

              <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
              </div>

              <div class="mb-2">
                <label class="form-label">Status Keanggotaan</label>
                <input type="text" class="form-control text-success" value="Aktif" disabled>
              </div>
            </div>
          </div>

          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Ganti Password</h6>

              <form action="password_update.php" method="post">
                <div class="mb-2">
                  <label>Password Lama</label>
                  <input type="password" name="password_lama" class="form-control" required>
                </div>

                <div class="mb-2">
                  <label>Password Baru</label>
                  <input type="password" name="password_baru" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label>Konfirmasi Password Baru</label>
                  <input type="password" name="konfirmasi_password" class="form-control" required>
                </div>

                <button class="btn btn-warning">Ubah Password</button>
              </form>
            </div>
          </div>

        </div>

      </div>
    </div>

  </main>
</div>

</body>
</html>
