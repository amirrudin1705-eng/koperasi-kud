<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ambil data anggota */
$qAnggota = mysqli_query($conn, "
    SELECT a.id_anggota, u.nama, u.foto
    FROM anggota a
    JOIN users u ON a.id_user = u.id_user
    WHERE a.id_user = '$id_user'
");
$dataAnggota = mysqli_fetch_assoc($qAnggota);

$id_anggota = $dataAnggota['id_anggota'] ?? 0;
$namaUser   = $dataAnggota['nama'] ?? 'Anggota';
$fotoProfil = $dataAnggota['foto']
    ? "../assets/uploads/profile/".$dataAnggota['foto']
    : "../assets/uploads/profile/default.png";

if (!$id_anggota) {
    die('Data anggota tidak valid');
}

/* cek pinjaman aktif */
$qAktif = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM pengajuan_pinjaman
    WHERE id_anggota = '$id_anggota'
      AND status = 'berjalan'
");
$punyaPinjamanAktif = (mysqli_fetch_assoc($qAktif)['total'] ?? 0) > 0;

/* ambil bunga */
$qBunga = mysqli_query($conn, "SELECT bunga_default FROM pengaturan LIMIT 1");
$bunga = (float)(mysqli_fetch_assoc($qBunga)['bunga_default'] ?? 0);

/* ambil hasil simulasi dari session */
$simulasi = $_SESSION['simulasi'] ?? null;
unset($_SESSION['simulasi']);

function rupiah($n) {
    return 'Rp ' . number_format($n,0,',','.');
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

<!-- SIDEBAR (SAMA PERSIS) -->
<aside class="sidebar p-3">
  <h5 class="fw-bold text-center mb-4">KUD Simpan Pinjam</h5>
  <ul class="nav flex-column gap-1">
    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="profil.php"><i class="bi bi-person"></i> Profil Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="simpanan.php"><i class="bi bi-wallet2"></i> Simpanan Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="pinjaman.php"><i class="bi bi-file-text"></i> Pinjaman Saya</a></li>
    <li class="nav-item"><a class="nav-link active" href="ajukan_pinjaman.php"><i class="bi bi-pencil-square"></i> Ajukan Pinjaman</a></li>
    <li class="nav-item"><a class="nav-link" href="angsuran.php"><i class="bi bi-clock-history"></i> Riwayat Angsuran</a></li>
    <hr>
    <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<main class="flex-fill bg-light">

<!-- TOPBAR (SAMA PERSIS) -->
<div class="p-3 bg-white shadow-sm">
    <h5 class="fw-semibold mb-0">Ajukan Pinjaman</h5>
</div>

<div class="container-fluid p-4">

<div class="card shadow-sm">
<div class="card-body">

<?php if ($punyaPinjamanAktif): ?>

<div class="alert alert-warning">
  Anda masih memiliki pinjaman aktif.<br>
  Silakan lunasi pinjaman sebelumnya terlebih dahulu.
</div>

<?php else: ?>

<form method="post" action="helpers/ajukan_pinjaman_process.php">

<div class="mb-3">
  <h6 class="fw-semibold mb-3">Jumlah Pinjaman</h6>
<input
  type="text"
  id="jumlah_display"
  name="jumlah_pinjaman"
  class="form-control"
  placeholder="Rp 0"
  autocomplete="off"
  required
  value="<?= isset($simulasi['jumlah']) ? 'Rp '.number_format($simulasi['jumlah'],0,',','.') : '' ?>">

<input
  type="hidden"
  id="jumlah_asli"
  name="jumlah"
  value="<?= $simulasi['jumlah'] ?? '' ?>">
</div>

<button name="aksi" value="simulasi" class="btn btn-secondary">
  Simulasikan Pinjaman
</button>

<?php if ($simulasi): ?>

<div class="table-responsive mt-4">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
  <th>Tenor</th>
  <th>Bunga</th>
  <th>Cicilan / Bulan</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach ($simulasi['hasil'] as $row): ?>
<tr>
  <td><?= $row['tenor'] ?> Bulan</td>
  <td><?= $bunga ?>%</td>
  <td><?= rupiah($row['cicilan']) ?></td>
  <td>
    <button
      name="aksi"
      value="ajukan"
      class="btn btn-primary-custom"
      onclick="this.form.tenor.value='<?= $row['tenor'] ?>'">
      Ajukan
    </button>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<input type="hidden" name="jumlah" value="<?= $simulasi['jumlah'] ?>">
<input type="hidden" name="tenor">

<?php endif; ?>

</form>

<?php endif; ?>

</div>
</div>

</div>
</main>
</div>

<!-- JAVASCRIPT FORMAT RUPIAH & SYNC JUMLAH -->
<script>
(function () {
  const display = document.getElementById('jumlah_display');
  const hidden  = document.getElementById('jumlah_asli');
  const form    = display ? display.closest('form') : null;

  if (!display || !hidden || !form) return;

  display.addEventListener('input', function () {
    let angka = this.value.replace(/[^\d]/g, '');
    hidden.value = angka;

    this.value = angka
      ? 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
      : '';
  });

  form.addEventListener('submit', function (e) {
    let angka = display.value.replace(/[^\d]/g, '');
    hidden.value = angka;

    if (!angka || parseInt(angka) <= 0) {
      e.preventDefault();
      alert('Masukkan jumlah pinjaman terlebih dahulu');
    }
  });
})();
</script>

</body>
</html>

