<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = (int)$_SESSION['id_user'];

/* ambil id_anggota */
$q = mysqli_query($conn,"SELECT id_anggota FROM anggota WHERE id_user=$id_user");
$a = mysqli_fetch_assoc($q);
$id_anggota = (int)$a['id_anggota'];

if (!isset($_POST['barang'])) {
    die("Data barang tidak ada");
}

foreach ($_POST['barang'] as $item) {

    $id_barang = (int)$item['id'];
    $jumlah    = (int)$item['jumlah'];

    if ($id_barang <= 0 || $jumlah <= 0) continue;

    /* ambil harga */
    $qBarang = mysqli_query($conn,"
        SELECT harga_jual FROM barang WHERE id_barang=$id_barang
    ");
    $b = mysqli_fetch_assoc($qBarang);
    $harga = (float)$b['harga_jual'];

    mysqli_query($conn,"
        INSERT INTO transaksi_barang
        (id_barang, id_anggota, jumlah, harga, jenis_transaksi, status, tanggal_transaksi)
        VALUES
        ($id_barang, $id_anggota, $jumlah, $harga, 'penjualan', 'menunggu', NOW())
    ");
}

header("Location: transaksi_barang.php");
exit;
