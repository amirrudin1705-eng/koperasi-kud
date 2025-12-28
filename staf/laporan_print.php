<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$awal  = $_GET['awal'];
$akhir = $_GET['akhir'];

$simpanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah) total 
    FROM simpanan 
    WHERE tanggal BETWEEN '$awal' AND '$akhir'
"));

$angsuran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) total 
    FROM angsuran 
    WHERE tanggal_bayar BETWEEN '$awal' AND '$akhir'
"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Keuangan</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body onload="window.print()">

<h3 align="center">LAPORAN KEUANGAN KOPERASI</h3>
<p align="center">Periode <?= $awal ?> s/d <?= $akhir ?></p>

<table>
    <tr>
        <th>Jenis</th>
        <th>Total</th>
    </tr>
    <tr>
        <td>Total Simpanan</td>
        <td>Rp <?= number_format($simpanan['total'] ?? 0,0,',','.') ?></td>
    </tr>
    <tr>
        <td>Total Angsuran</td>
        <td>Rp <?= number_format($angsuran['total'] ?? 0,0,',','.') ?></td>
    </tr>
    <tr>
        <th>Total Dana Masuk</th>
        <th>Rp <?= number_format(($simpanan['total'] ?? 0)+($angsuran['total'] ?? 0),0,',','.') ?></th>
    </tr>
</table>

<br>
<p>
    Dicetak oleh: <b>Staff Keuangan</b><br>
    Tanggal cetak: <?= date('d-m-Y') ?>
</p>

</body>
</html>
