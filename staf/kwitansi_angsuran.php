<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id = $_GET['id'];

// ambil data angsuran
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        a.id_angsuran,
        a.angsuran_ke,
        a.tanggal_bayar,
        a.jumlah_bayar,
        p.cicilan,
        p.tenor,
        u.nama
    FROM angsuran a
    JOIN pengajuan_pinjaman p ON a.id_pengajuan = p.id_pengajuan
    JOIN anggota ag ON p.id_anggota = ag.id_anggota
    JOIN users u ON ag.id_user = u.id_user
    WHERE a.id_angsuran='$id'
"));

// hitung total bayar & sisa
$totalBayar = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT IFNULL(SUM(jumlah_bayar),0) total
    FROM angsuran
    WHERE id_pengajuan = (
        SELECT id_pengajuan FROM angsuran WHERE id_angsuran='$id'
    )
"));

$totalPinjaman = $data['cicilan'] * $data['tenor'];
$sisa = $totalPinjaman - $totalBayar['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Angsuran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .kwitansi {
            width: 700px;
            margin: auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        td {
            padding: 6px;
        }
        .ttd {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body onload="window.print()">

<div class="kwitansi">
    <div class="judul">
        KWITANSI PEMBAYARAN ANGSURAN<br>
        KOPERASI UNIT DESA
    </div>

    <table>
        <tr>
            <td width="200">Nama Anggota</td>
            <td>: <?= $data['nama'] ?></td>
        </tr>
        <tr>
            <td>Angsuran Ke</td>
            <td>: <?= $data['angsuran_ke'] ?></td>
        </tr>
        <tr>
            <td>Tanggal Bayar</td>
            <td>: <?= $data['tanggal_bayar'] ?></td>
        </tr>
        <tr>
            <td>Jumlah Bayar</td>
            <td>: Rp <?= number_format($data['jumlah_bayar'],0,',','.') ?></td>
        </tr>
        <tr>
            <td>Sisa Pinjaman</td>
            <td>: Rp <?= number_format($sisa,0,',','.') ?></td>
        </tr>
    </table>

    <div class="ttd">
        <?= date('d-m-Y') ?><br>
        Petugas Keuangan<br><br><br>
        ( __________________ )
    </div>
</div>

</body>
</html>
