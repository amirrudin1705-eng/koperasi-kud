<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

require_once 'helpers/simpanan_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Simpanan Saya</title>
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
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="profil.php">
        <i class="bi bi-person"></i> Profil Saya
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link active" href="simpanan.php">
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

    <hr>

    <li class="nav-item">
      <a class="nav-link text-danger" href="../auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</aside>

<!-- MAIN -->
<main class="flex-fill bg-light">

  <!-- TOPBAR -->
  <div class="p-3 bg-white shadow-sm">
    <h5 class="fw-semibold mb-0">Simpanan Saya</h5>
  </div>

  <div class="container-fluid p-4">

    <!-- RINGKASAN SIMPANAN -->
    <div class="row g-3 mb-4">

      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <small class="text-muted">Simpanan Pokok</small>
            <h5 class="fw-bold mb-0">
              Rp <?= number_format($totalPokok, 0, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <small class="text-muted">Simpanan Wajib</small>
            <h5 class="fw-bold mb-0">
              Rp <?= number_format($totalWajib, 0, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm border-primary">
          <div class="card-body">
            <small class="text-muted">Total Simpanan</small>
            <h5 class="fw-bold mb-0">
              Rp <?= number_format($totalSimpanan, 0, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>

    </div>

    <!-- RIWAYAT SIMPANAN -->
    <div class="card shadow-sm">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Riwayat Simpanan</h6>

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Jenis Simpanan</th>
                <th>Nominal</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($qRiwayat) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($qRiwayat)): ?>
                <tr>
                  <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                  <td><?= ucfirst($row['jenis_simpanan']) ?></td>
                  <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                  <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    Belum ada data simpanan
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</main>
</div>

</body>
</html>
