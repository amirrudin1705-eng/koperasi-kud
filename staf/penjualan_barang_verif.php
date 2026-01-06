<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id   = (int)($_GET['id'] ?? 0);
$aksi = $_GET['aksi'] ?? '';

/* AMBIL TRANSAKSI */
$trx = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT id_barang, jumlah
    FROM transaksi_barang
    WHERE id_transaksi = $id
"));

if (!$trx) {
    die('Transaksi tidak ditemukan');
}

mysqli_begin_transaction($conn);

try {

    if ($aksi === 'setujui') {

        /* UPDATE STATUS */
        mysqli_query($conn,"
            UPDATE transaksi_barang
            SET status = 'disetujui'
            WHERE id_transaksi = $id
        ");

        /* POTONG STOK */
        mysqli_query($conn,"
            UPDATE barang
            SET stok = stok - {$trx['jumlah']}
            WHERE id_barang = {$trx['id_barang']}
        ");

    } elseif ($aksi === 'tolak') {

        mysqli_query($conn,"
            UPDATE transaksi_barang
            SET status = 'ditolak'
            WHERE id_transaksi = $id
        ");
    }

    mysqli_commit($conn);
    header("Location: penjualan_barang.php?status=success");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    die('Gagal memproses transaksi');
}
