<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once 'helpers/angsuran_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Angsuran | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background:#f5f6fa; }
.sidebar { width:250px; background:#1f2937; min-height:100vh; position:fixed; }
.sidebar a { display:block; padding:12px 20px; color:#cbd5e1; text-decoration:none; }
.sidebar a:hover, .sidebar a.active { background:#374151; color:#fff; }
.content { margin-left:250px; padding:24px; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i>Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i>Simpanan</a>
    <a href="barang.php"><i class="bi bi-box-seam me-2"></i>Barang</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pinjaman</a>
    <a href="angsuran.php" class="active"><i class="bi bi-arrow-repeat me-2"></i>Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</aside>

<!-- CONTENT -->
<main class="content">

<h4 class="mb-4">Data Angsuran</h4>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Angsuran Ke</th>
    <th>Jumlah Bayar</th>
    <th>Tanggal Bayar</th>
</tr>
</thead>
<tbody>

<?php if (!empty($dataAngsuran)): ?>
<?php $no=1; foreach ($dataAngsuran as $row): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['nama']); ?></td>
    <td><?= $row['angsuran_ke']; ?></td>
    <td>Rp <?= number_format($row['jumlah_bayar'],0,',','.'); ?></td>
    <td>
        <?= !empty($row['tanggal_bayar'])
            ? date('d M Y', strtotime($row['tanggal_bayar']))
            : '-' ?>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="5" class="text-center text-muted">
        Belum ada data angsuran
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
