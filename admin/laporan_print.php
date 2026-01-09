<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

require_once '../config/database.php';
require_once 'helpers/laporan_data.php';

/* fallback anti warning */
$detailSimpanan  = $detailSimpanan  ?? false;
$detailAngsuran  = $detailAngsuran  ?? false;
$detailPenjualan = $detailPenjualan ?? false;
?>
<!DOCTYPE html>
<html>
<head>
<title>Cetak Laporan Koperasi</title>
<style>
body { font-family: Arial; font-size: 12px; }
h2, h3 { text-align: center; }
table { width:100%; border-collapse: collapse; margin-top:10px; }
th, td { border:1px solid #000; padding:6px; }
th { background:#eee; }
.section { margin-top:25px; }
</style>
</head>

<body onload="window.print()">

<h2>LAPORAN KEUANGAN KOPERASI</h2>
<h3>
Periode
<?= $bulan ? date('F', mktime(0,0,0,$bulan,1)) : 'Semua Bulan'; ?>
<?= $tahun ?>
</h3>

<!-- ================= RINGKASAN ================= -->
<table>
<tr><th colspan="2">Ringkasan</th></tr>
<tr>
    <td>Total Simpanan</td>
    <td>Rp <?= number_format($totalSimpanan,0,',','.') ?></td>
</tr>
<tr>
    <td>Angsuran Masuk</td>
    <td>Rp <?= number_format($totalAngsuranMasuk,0,',','.') ?></td>
</tr>
<tr>
    <td>Penjualan Barang</td>
    <td>Rp <?= number_format($totalPenjualanBarang,0,',','.') ?></td>
</tr>
<tr>
    <th>Total Dana Masuk</th>
    <th>
        Rp <?= number_format(
            $totalSimpanan + $totalAngsuranMasuk + $totalPenjualanBarang,
            0,',','.'
        ) ?>
    </th>
</tr>
</table>

<!-- ================= DETAIL SIMPANAN ================= -->
<div class="section">
<h3>Detail Simpanan Anggota</h3>
<table>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Jumlah</th>
</tr>
<?php if ($detailSimpanan && mysqli_num_rows($detailSimpanan) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailSimpanan)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d-m-Y', strtotime($r['tanggal'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota']) ?></td>
    <td>Rp <?= number_format($r['jumlah'],0,',','.') ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="4" align="center">Tidak ada data simpanan</td></tr>
<?php endif; ?>
</table>
</div>

<!-- ================= DETAIL ANGSURAN ================= -->
<div class="section">
<h3>Detail Angsuran Pinjaman</h3>
<table>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Jumlah Bayar</th>
</tr>
<?php if ($detailAngsuran && mysqli_num_rows($detailAngsuran) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailAngsuran)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d-m-Y', strtotime($r['tanggal_bayar'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota']) ?></td>
    <td>Rp <?= number_format($r['jumlah_bayar'],0,',','.') ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="4" align="center">Tidak ada data angsuran</td></tr>
<?php endif; ?>
</table>
</div>

<!-- ================= DETAIL PENJUALAN ================= -->
<div class="section">
<h3>Detail Transaksi Penjualan Barang</h3>
<table>
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
<?php if ($detailPenjualan && mysqli_num_rows($detailPenjualan) > 0): ?>
<?php $no=1; while($r=mysqli_fetch_assoc($detailPenjualan)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d-m-Y', strtotime($r['tanggal_transaksi'])) ?></td>
    <td><?= htmlspecialchars($r['nama_anggota'] ?? 'Umum') ?></td>
    <td><?= htmlspecialchars($r['nama_barang']) ?></td>
    <td><?= $r['jumlah'].' '.$r['satuan'] ?></td>
    <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
    <td><?= ucfirst($r['metode_pembayaran']) ?></td>
    <td><?= ucfirst($r['status']) ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="8" align="center">Tidak ada transaksi</td></tr>
<?php endif; ?>
</table>
</div>

<p style="margin-top:30px;">
Dicetak oleh: <b>Admin</b><br>
Tanggal cetak: <?= date('d-m-Y') ?>
</p>

</body>
</html>
