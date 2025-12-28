<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';
require_once 'helpers/pinjaman_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pinjaman | Admin KUD</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f6fa;
}
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #1f2937;
    position: fixed;
}
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #cbd5e1;
    text-decoration: none;
}
.sidebar a:hover,
.sidebar a.active {
    background: #374151;
    color: #fff;
}
.content {
    margin-left: 250px;
    padding: 24px;
}
.card {
    border-radius: 12px;
}
.badge-menunggu { background: #facc15; }
.badge-disetujui { background: #22c55e; }
.badge-ditolak { background: #ef4444; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i>Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i>Simpanan</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pengajuan Pinjaman</a>
    <a href="pinjaman.php" class="active"><i class="bi bi-cash-coin me-2"></i>Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i>Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right me-2"></i>Logout
    </a>
</aside>

<!-- CONTENT -->
<main class="content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Data Pinjaman Anggota</h4>
</div>

<!-- SUMMARY -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <small class="text-muted">Total Pinjaman Aktif</small>
            <h4 class="fw-bold text-primary mb-0">
                Rp <?= number_format($totalPinjamanAktif, 0, ',', '.'); ?>
            </h4>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="card shadow-sm">
<div class="card-body">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Jumlah</th>
    <th>Tenor</th>
    <th>Cicilan</th>
    <th>Status</th>
    <th>Tanggal</th>
</tr>
</thead>
<tbody>

<?php if (!empty($dataPinjaman)): ?>
<?php $no=1; foreach ($dataPinjaman as $row): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($row['nama']); ?></td>
<td>Rp <?= number_format($row['jumlah_pinjaman'],0,',','.'); ?></td>
<td><?= $row['tenor']; ?> bln</td>
<td>Rp <?= number_format($row['cicilan'],0,',','.'); ?></td>
<td>
  <span class="badge <?= $row['status']=='aktif'?'bg-success':'bg-secondary'; ?>">
    <?= ucfirst($row['status']); ?>
  </span>
</td>
<td>
  <?= !empty($row['tanggal_pengajuan'])
      ? date('d M Y', strtotime($row['tanggal_pengajuan']))
      : '-' ?>
</td>

</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="7" class="text-center text-muted">
        Belum ada data pinjaman
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>
</div>

</main>
</body>
</html>
