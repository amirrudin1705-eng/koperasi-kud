<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: barang.php");
    exit;
}

/* CEK APAKAH BARANG SUDAH PERNAH DITRANSAKSI */
$cek = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM transaksi_barang
    WHERE id_barang = $id
"));

if ($cek['total'] > 0) {
    header("Location: barang.php?error=tidak_bisa_hapus");
    exit;
}

/* HAPUS BARANG */
mysqli_query($conn, "DELETE FROM barang WHERE id_barang = $id");

header("Location: barang.php");
exit;
