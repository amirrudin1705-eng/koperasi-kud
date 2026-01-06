<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$awal  = $_GET['awal'];
$akhir = $_GET['akhir'];

/* ===============================
 * TOTAL SIMPANAN
 * =============================== */
$simpanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah),0) AS total 
    FROM simpanan 
    WHERE tanggal BETWEEN '$awal' AND '$akhir'
"));

/* ===============================
 * TOTAL ANGSURAN
 * =============================== */
$angsuran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar),0) AS total 
    FROM angsuran 
    WHERE tanggal_bayar BETWEEN '$awal' AND '$akhir'
"));

/* ===============================
 * TOTAL PENJUALAN BARANG (BARU)
 * =============================== */
$penjualan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah * harga),0) AS total
    FROM transaksi_barang
    WHERE tanggal_transaksi BETWEEN '$awal' AND '$akhir'
"));

/* ===============================
 * TOTAL DANA MASUK
 * =============================== */
$totalDana =
    ($simpanan['total'] ?? 0) +
    ($angsuran['total'] ?? 0) +
    ($penjualan['total'] ?? 0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Keuangan</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #eee; }
        h3, p { text-align: center; }
    </style>
</head>

<body onload="window.print()">

<h3>LAPORAN KEUANGAN KOPERASI</h3>
<p>Periode <?= date('d M Y', strtotime($awal)) ?> s/d <?= date('d M Y', strtotime($akhir)) ?></p>

<table>
    <tr>
        <th>Jenis</th>
        <th>Total</th>
    </tr>
    <tr>
        <td>Total Simpanan</td>
        <td>Rp <?= number_format($simpanan['total'],0,',','.') ?></td>
    </tr>
    <tr>
        <td>Total Angsuran</td>
        <td>Rp <?= number_format($angsuran['total'],0,',','.') ?></td>
    </tr>
    <tr>
        <td><b>Total Penjualan Barang</b></td>
        <td><b>Rp <?= number_format($penjualan['total'],0,',','.') ?></b></td>
    </tr>
    <tr>
        <th>TOTAL DANA MASUK</th>
        <th>Rp <?= number_format($totalDana,0,',','.') ?></th>
    </tr>
</table>

<br>
<p align="right">
    Dicetak oleh: <b>Staff Keuangan</b><br>
    Tanggal cetak: <?= date('d-m-Y') ?>
</p>

</body>
</html>
