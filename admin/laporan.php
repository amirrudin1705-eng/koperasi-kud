<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';
require_once 'helpers/laporan_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Koperasi | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body { font-family:'Poppins',sans-serif; background:#f5f6fa; }
.sidebar { width:250px; min-height:100vh; background:#1f2937; position:fixed; }
.sidebar a { display:block; padding:12px 20px; color:#cbd5e1; text-decoration:none; }
.sidebar a:hover, .sidebar a.active { background:#374151; color:#fff; }
.content { margin-left:250px; padding:24px; }
.card-stat { border:none; border-radius:12px; }
.card-stat i { font-size:28px; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i> Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i> Simpanan</a>
    <a href="barang.php"><i class="bi bi-box-seam me-2"></i> Barang</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i> Angsuran</a>
    <a href="laporan.php" class="active"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
    <a href="../auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</aside>

<main class="content">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Laporan Koperasi</h4>
    <span class="text-muted">
        <?= date('F Y', mktime(0,0,0,$bulan ?: date('m'),1,$tahun)); ?>
    </span>
</div>

<!-- FILTER -->
<form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="bulan" class="form-select">
            <option value="">-- Bulan --</option>
            <?php for($i=1;$i<=12;$i++): ?>
            <option value="<?= $i ?>" <?= ($bulan==$i?'selected':'') ?>>
                <?= date('F', mktime(0,0,0,$i,1)) ?>
            </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="tahun" class="form-select">
            <?php for($y=date('Y');$y>=2022;$y--): ?>
            <option value="<?= $y ?>" <?= ($tahun==$y?'selected':'') ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="laporan.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- STAT -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-stat p-3 shadow-sm">
            <small>Total Simpanan</small>
            <h4 class="fw-bold text-success">Rp <?= number_format($totalSimpanan,0,',','.') ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 shadow-sm">
            <small>Angsuran Masuk</small>
            <h4 class="fw-bold text-info">Rp <?= number_format($totalAngsuranMasuk,0,',','.') ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 shadow-sm">
            <small>Penjualan Barang</small>
            <h4 class="fw-bold text-primary">Rp <?= number_format($totalPenjualanBarang,0,',','.') ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 shadow-sm">
            <small>Total Dana Masuk</small>
            <h4 class="fw-bold">
                Rp <?= number_format(
                    $totalSimpanan + $totalAngsuranMasuk + $totalPenjualanBarang,
                    0,',','.'
                ) ?>
            </h4>
        </div>
    </div>
</div>

<!-- TUNGGAKAN -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<h6 class="fw-semibold mb-3">Tunggakan Per Anggota</h6>
<table class="table table-bordered">
<thead class="table-light">
<tr>
    <th>No</th><th>Nama</th><th>Pinjaman</th><th>Dibayar</th><th>Tunggakan</th>
</tr>
</thead>
<tbody>
<?php if (!empty($dataTunggakan)): ?>
<?php $no=1; foreach($dataTunggakan as $r): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $r['nama_anggota'] ?></td>
    <td>Rp <?= number_format($r['jumlah_pinjaman'],0,',','.') ?></td>
    <td>Rp <?= number_format($r['total_bayar'],0,',','.') ?></td>
    <td class="fw-bold text-danger">Rp <?= number_format($r['tunggakan'],0,',','.') ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>
</table>
<a href="laporan_print.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
   target="_blank"
   class="btn btn-outline-primary mb-4">
   <i class="bi bi-printer"></i> Cetak Laporan
</a>

</div>
</div>

</main>
</body>
</html>
