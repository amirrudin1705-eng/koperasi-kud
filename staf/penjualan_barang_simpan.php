<?php
require '../auth/auth_staf.php';
require '../config/database.php';

/* =========================
 * AMBIL & SANITASI INPUT
 * ========================= */
$id_barang   = (int) ($_POST['id_barang'] ?? 0);
$jumlah      = (int) ($_POST['jumlah'] ?? 0);
$id_anggota  = !empty($_POST['id_anggota']) ? (int) $_POST['id_anggota'] : null;
$metode      = $_POST['metode_pembayaran'] ?? '';

/* =========================
 * VALIDASI AWAL
 * ========================= */
if ($id_barang <= 0 || $jumlah <= 0 || !in_array($metode, ['tunai','simpanan'])) {
    header("Location: penjualan_barang_tambah.php?error=invalid");
    exit;
}

/* =========================
 * AMBIL DATA BARANG (HARGA & STOK ASLI)
 * ========================= */
$qBarang = mysqli_query($conn, "
    SELECT stok, harga_jual
    FROM barang
    WHERE id_barang = $id_barang
    LIMIT 1
");
$barang = mysqli_fetch_assoc($qBarang);

if (!$barang) {
    header("Location: penjualan_barang_tambah.php?error=barang");
    exit;
}

/* =========================
 * VALIDASI STOK
 * ========================= */
if ($barang['stok'] < $jumlah) {
    header("Location: penjualan_barang_tambah.php?error=stok");
    exit;
}

$harga_satuan = (float) $barang['harga_jual'];
$total        = $harga_satuan * $jumlah;

/* =========================
 * VALIDASI SIMPANAN (JIKA DIPAKAI)
 * ========================= */
if ($metode === 'simpanan') {

    if (!$id_anggota) {
        header("Location: penjualan_barang_tambah.php?error=saldo");
        exit;
    }

    $qSaldo = mysqli_query($conn, "
        SELECT 
            COALESCE(SUM(
                CASE 
                    WHEN jenis = 'masuk' THEN jumlah
                    WHEN jenis = 'keluar' THEN -jumlah
                END
            ), 0) AS saldo
        FROM simpanan
        WHERE id_anggota = $id_anggota
    ");
    $saldo = (float) mysqli_fetch_assoc($qSaldo)['saldo'];

    if ($saldo < $total) {
        header("Location: penjualan_barang_tambah.php?error=saldo");
        exit;
    }
}

/* =========================
 * PROSES TRANSAKSI (AMAN)
 * ========================= */
mysqli_begin_transaction($conn);

try {

    /* INSERT TRANSAKSI BARANG */
    mysqli_query($conn, "
        INSERT INTO transaksi_barang
        (id_barang, jenis_transaksi, jumlah, harga, tanggal_transaksi, id_anggota, metode_pembayaran)
        VALUES
        ($id_barang, 'penjualan', $jumlah, $harga_satuan, CURDATE(),
         ".($id_anggota ?: "NULL").", '$metode')
    ");

    /* UPDATE STOK BARANG */
    mysqli_query($conn, "
        UPDATE barang
        SET stok = stok - $jumlah
        WHERE id_barang = $id_barang
    ");

    /* POTONG SIMPANAN JIKA DIPAKAI */
    if ($metode === 'simpanan') {
        mysqli_query($conn, "
            INSERT INTO simpanan (id_anggota, jumlah, jenis)
            VALUES ($id_anggota, $total, 'keluar')
        ");
    }

    mysqli_commit($conn);

    header("Location: penjualan_barang.php?status=success");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    header("Location: penjualan_barang_tambah.php?error=fail");
    exit;
}
