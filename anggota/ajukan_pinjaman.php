<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$qAnggota = mysqli_query($conn, "
    SELECT id_anggota 
    FROM anggota 
    WHERE id_user = '$id_user'
");
$dataAnggota = mysqli_fetch_assoc($qAnggota);
$id_anggota  = $dataAnggota['id_anggota'] ?? 0;

/* ===== VALIDASI WAJIB ===== */
if (!$id_anggota) {
    echo "<script>
        alert('Data keanggotaan Anda belum terdaftar. Silakan hubungi admin.');
        window.location='dashboard.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ajukan Pinjaman</title>
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
      <a class="nav-link active" href="ajukan_pinjaman.php">
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
    <h5 class="fw-semibold mb-0">Ajukan Pinjaman</h5>
  </div>

  <div class="container-fluid p-4">

    <div class="card shadow-sm">
      <div class="card-body">

        <form method="post" action="helpers/ajukan_pinjaman_process.php">

          <!-- hidden -->
          <input type="hidden" name="id_anggota" value="<?= $id_anggota ?>">
          <input type="hidden" name="jumlah_pinjaman" id="jumlah_asli">
          <input type="hidden" name="tenor" id="tenor">
          <input type="hidden" name="bunga" id="bunga">
          <input type="hidden" name="cicilan" id="cicilan">

          <!-- jumlah -->
          <div class="mb-3">
            <label class="form-label">Jumlah Pinjaman</label>
            <input
              type="text"
              id="jumlah"
              class="form-control"
              placeholder="Rp 0"
            >
          </div>

          <!-- TOMBOL (DIKUNCI SEJAJAR) -->
          <div class="d-flex align-items-center gap-2 mt-2">
            <button
              type="button"
              class="btn btn-secondary"
              onclick="simulasi()"
            >
              Simulasikan Pinjaman
            </button>

            <button
              type="submit"
              class="btn btn-primary-custom"
            >
              Ajukan Pinjaman
            </button>
          </div>

          <!-- HASIL SIMULASI -->
          <div id="hasil" style="display:none" class="mt-4">
            <h6 class="fw-semibold">Simulasi Pinjaman</h6>

            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Tenor</th>
                    <th>Bunga</th>
                    <th>Cicilan / Bulan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="tabelSimulasi"></tbody>
              </table>
            </div>
          </div>

        </form>

      </div>
    </div>

  </div>
</main>
</div>

<!-- LOAD JS -->
<script src="../assets/js/simulasi_pinjaman.js" defer></script>

</body>
</html>
