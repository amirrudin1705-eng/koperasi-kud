<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;


}

$id_user = $_SESSION['id_user'];

/* ===============================
   DATA USER
================================ */
$qUser = mysqli_query($conn, "
    SELECT nama, foto
    FROM users
    WHERE id_user = '$id_user'
");
$user = mysqli_fetch_assoc($qUser);

$fotoProfil = !empty($user['foto'])
    ? "../assets/uploads/profile/" . $user['foto'] . "?v=" . time()
    : "../assets/uploads/profile/default.png";

/* ===============================
   DATA DASHBOARD
================================ */
require_once 'helpers/simpanan_data.php';
require_once 'helpers/dashboard_data.php';

if (isset($statusKeanggotaan) && $statusKeanggotaan !== 'Aktif') {
    echo "<script>
        alert('Akun Anda saat ini NONAKTIF. Silakan hubungi admin koperasi.');
        window.location.href = '../auth/logout.php';
    </script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Anggota</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex min-vh-100">

<!-- SIDEBAR -->
<aside class="sidebar p-3">
  <h5 class="fw-bold text-center mb-4">KUD Simpan Pinjam</h5>
  <ul class="nav flex-column gap-1">
    <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="profil.php"><i class="bi bi-person"></i> Profil Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="simpanan.php"><i class="bi bi-wallet2"></i> Simpanan Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="pinjaman.php"><i class="bi bi-file-text"></i> Pinjaman Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="ajukan_pinjaman.php"><i class="bi bi-pencil-square"></i> Ajukan Pinjaman</a></li>
    <li class="nav-item"><a class="nav-link" href="angsuran.php"><i class="bi bi-clock-history"></i> Riwayat Angsuran</a></li>
    <hr>
    <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<main class="flex-fill bg-light">
  

<div class="d-flex justify-content-end align-items-center p-3 bg-white shadow-sm">
  <div class="text-end me-3">
    <div class="fw-semibold"><?= htmlspecialchars($_SESSION['nama'] ?? 'Anggota'); ?></div>
    <small class="text-muted">Anggota</small>
  </div>
  <img src="<?= $fotoProfil ?>" class="rounded-circle" width="42" height="42" style="object-fit:cover;">
</div>

<div class="container-fluid p-4">

<!-- RINGKASAN -->
<div class="row g-3 mb-3">
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small>Total Simpanan</small><h5>Rp <?= number_format($totalSimpanan,0,',','.') ?></h5></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small>Pinjaman Aktif</small><h5>Rp <?= number_format($pinjamanAktif,0,',','.') ?></h5></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small>Sisa Angsuran</small><h5>Rp <?= number_format($sisaAngsuran,0,',','.') ?></h5></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small>Status Keanggotaan</small><h5 class="text-success"><?= $statusKeanggotaan ?></h5></div></div></div>
</div>

<!-- NOTIFIKASI -->
<?php if ($jatuhTempo && $sisaAngsuran > 0): ?>
<div class="row">
  <div class="col-12">
    <div class="card border-warning shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h6 class="fw-bold mb-1">⚠️ Pengingat Angsuran</h6>
          <span class="text-muted">
            Jatuh tempo pada <strong><?= date('d F Y', strtotime($jatuhTempo)) ?></strong> |
            Angsuran ke-<strong><?= $angsuranKe ?></strong> |
            Nominal <strong>Rp <?= number_format($nominalAngsuran,0,',','.') ?></strong>
          </span>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2">Segera Dibayar</span>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

</div>
</main>
</div>

</body>
</html>
