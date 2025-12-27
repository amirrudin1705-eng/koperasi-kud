<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../../auth/login.php");
    exit;
}

$id_anggota = $_POST['id_anggota'];
$jumlah     = $_POST['jumlah_pinjaman'];
$tenor      = $_POST['tenor'];
$bunga      = $_POST['bunga'];
$cicilan    = $_POST['cicilan'];

if (!$jumlah || !$tenor || !$bunga || !$cicilan) {
    echo "<script>alert('Silakan lakukan simulasi dan pilih tenor');window.history.back();</script>";
    exit;
}

mysqli_query($conn, "
INSERT INTO pengajuan_pinjaman
(id_anggota, tanggal_pengajuan, jumlah_pinjaman, tenor, bunga, cicilan)
VALUES
('$id_anggota', CURDATE(), '$jumlah', '$tenor', '$bunga', '$cicilan')
");

echo "<script>
alert('Pengajuan pinjaman berhasil dikirim');
window.location='../pinjaman.php';
</script>";
