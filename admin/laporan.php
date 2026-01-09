<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';
require_once 'helpers/laporan_data.php';

/* ===============================
 * LABEL PERIODE
 * =============================== */
$labelPeriode = 'Semua Periode';

if (!empty($bulan) && !empty($tahun)) {
    $labelPeriode = date('F Y', mktime(0,0,0,$bulan,1,$tahun));
} elseif (!empty($tahun)) {
    $labelPeriode = "Tahun $tahun";
}
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
    <span class="text-muted"><?= $labelPeriode ?></span>
</div>

<!-- FILTER -->
<form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="bulan" class="form-select">
            <option value="">-- Semua Bulan --</option>
            <?php for($i=1;$i<=12;$i++): ?>
            <option value="<?= $i ?>" <?= ($bulan==$i?'selected':'') ?>>
                <?= date('F', mktime(0,0,0,$i,1)) ?>
            </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="tahun" class="form-select">
            <option value="">-- Semua Tahun --</option>
            <?php for($y=date('Y');$y>=2022;$y--): ?>
            <option value="<?= $y ?>" <?= ($tahun==$y?'selected':'') ?>>
                <?= $y ?>
            </option>
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
<?php
$cards = [
    ['Total Simpanan', $totalSimpanan, 'success'],
    ['Angsuran Masuk', $totalAngsuranMasuk, 'info'],
    ['Penjualan Barang', $totalPenjualanBarang, 'primary'],
    ['Total Dana Masuk', $totalDanaMasuk, 'dark'],
];
foreach ($cards as $c):
?>
    <div class="col-md-3">
        <div class="card card-stat p-3 shadow-sm">
            <small><?= $c[0] ?></small>
            <h4 class="fw-bold text-<?= $c[2] ?>">
                Rp <?= number_format($c[1],0,',','.') ?>
            </h4>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- DETAIL SIMPANAN -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<h6 class="fw-semibold mb-3">Detail Simpanan Anggota</h6>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Jumlah</th>
</tr>
</thead>
<tbody>
<?php if ($detailSimpanan && mysqli_num_rows($detailSimpanan) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailSimpanan)): ?>
<tr>
<<<<<<< HEAD
    <td><?= $no++ ?></td>
    <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota']) ?></td>
    <td>Rp <?= number_format($r['jumlah'],0,',','.') ?></td>
=======
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['nama']); ?></td>
    <td>Rp <?= number_format($row['total_tagihan'],0,',','.'); ?></td>
    <td>Rp <?= number_format($row['total_bayar'],0,',','.'); ?></td>
    <td class="text-danger fw-bold">
        Rp <?= number_format($row['tunggakan'],0,',','.'); ?>
    </td>
>>>>>>> e67b832d1f9bd2ef40512f1aa1c2dd6dc22db51f
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="4" class="text-center text-muted">Tidak ada data simpanan</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- DETAIL ANGSURAN -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<h6 class="fw-semibold mb-3">Detail Angsuran Pinjaman</h6>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Jumlah Bayar</th>
</tr>
</thead>
<tbody>
<?php if ($detailAngsuran && mysqli_num_rows($detailAngsuran) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailAngsuran)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d M Y', strtotime($r['tanggal_bayar'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota']) ?></td>
    <td>Rp <?= number_format($r['jumlah_bayar'],0,',','.') ?></td>
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="4" class="text-center text-muted">Tidak ada data angsuran</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- DETAIL PENJUALAN -->
<div class="card shadow-sm">
<div class="card-body">
<h6 class="fw-semibold mb-3">Detail Transaksi Penjualan Barang</h6>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Pembayaran</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<?php if ($detailPenjualan && mysqli_num_rows($detailPenjualan) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailPenjualan)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d M Y', strtotime($r['tanggal_transaksi'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota'] ?? 'Umum') ?></td>
    <td><?= htmlspecialchars($r['nama_barang']) ?></td>
    <td><?= $r['jumlah'].' '.$r['satuan'] ?></td>
    <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
    <td><?= ucfirst($r['metode_pembayaran']) ?></td>
    <td><?= ucfirst($r['status']) ?></td>
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="8" class="text-center text-muted">Tidak ada transaksi</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

<a href="laporan_print.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
   target="_blank"
   class="btn btn-outline-primary">
   <i class="bi bi-printer"></i> Cetak Laporan
</a>

</div>
</div>

</main>
</body>
</html>
