<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';
require_once 'helpers/simpanan_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Simpanan | Admin KUD</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f6fa;
}

/* === LAYOUT UTAMA === */
.admin-wrapper {
    display: flex;
    min-height: 100vh;
}

/* === SIDEBAR (STABIL) === */
.sidebar {
    width: 250px;
    background: #1f2937;
    flex-shrink: 0;
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

/* === CONTENT === */
.content {
    flex: 1;
    padding: 24px;
}

/* CARD */
.card {
    border-radius: 12px;
}
</style>
</head>

<body>

<div class="admin-wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i>Anggota</a>
    <a href="simpanan.php" class="active"><i class="bi bi-wallet2 me-2"></i>Simpanan</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i>Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</aside>

<!-- CONTENT -->
<main class="content">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Data Simpanan Anggota</h4>
    <span class="text-muted">Admin</span>
</div>

<!-- TOTAL SIMPANAN -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <small class="text-muted">Total Simpanan Keseluruhan</small>
            <h4 class="fw-bold text-success mb-0">
                Rp <?= number_format($totalSimpanan, 0, ',', '.'); ?>
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
                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($dataSimpanan)): ?>
                <?php $no = 1; foreach ($dataSimpanan as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td class="text-capitalize"><?= $row['jenis_simpanan']; ?></td>
                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
                    <td>
                        <?= !empty($row['tanggal'])
                            ? date('d M Y', strtotime($row['tanggal']))
                            : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data simpanan
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>

</body>
</html>
