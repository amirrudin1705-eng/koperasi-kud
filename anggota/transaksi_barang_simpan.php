<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = (int)$_SESSION['id_user'];
$metode  = $_POST['metode_pembayaran'] ?? '';

if (!in_array($metode, ['cash','transfer','simpanan'])) {
    die("Metode pembayaran tidak valid");
}

/* ID ANGGOTA */
$a = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id_anggota FROM anggota WHERE id_user=$id_user
"));
$id_anggota = (int)$a['id_anggota'];

if (!isset($_POST['barang'])) {
    die("Data barang kosong");
}

/* ===============================
 * HITUNG TOTAL & SIAPKAN ITEM
 * =============================== */
$total = 0;
$items = [];

foreach ($_POST['barang'] as $item) {
    $id_barang = (int)$item['id'];
    $jumlah    = (int)$item['jumlah'];

    if ($id_barang <= 0 || $jumlah <= 0) continue;

    $b = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT harga_jual FROM barang WHERE id_barang=$id_barang
    "));
    $harga = (float)$b['harga_jual'];

    $subtotal = $harga * $jumlah;
    $total += $subtotal;

    $items[] = [
        'id_barang' => $id_barang,
        'jumlah'    => $jumlah,
        'harga'     => $harga
    ];
}

if ($total <= 0) {
    die("Total transaksi tidak valid");
}

/* ===============================
 * POTONG SIMPANAN (JIKA DIPILIH)
 * =============================== */
if ($metode === 'simpanan') {

    $saldo = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT COALESCE(SUM(jumlah),0) AS saldo
        FROM simpanan
        WHERE id_anggota=$id_anggota
    "))['saldo'];

    if ($saldo < $total) {
        echo "<script>
            alert('Saldo simpanan tidak mencukupi');
            window.location='transaksi_barang.php';
        </script>";
        exit;
    }

    /* CATAT PEMOTONGAN */
    mysqli_query($conn,"
        INSERT INTO simpanan
        (id_anggota, jumlah, jenis, tanggal)
        VALUES
        ($id_anggota, -$total, 'potong', NOW())
    ");
}

/* ===============================
 * SIMPAN KE transaksi_barang
 * =============================== */
foreach ($items as $i) {
    mysqli_query($conn,"
        INSERT INTO transaksi_barang
        (id_barang, id_anggota, jumlah, harga, metode_pembayaran, status, jenis_transaksi, tanggal_transaksi)
        VALUES
        ({$i['id_barang']}, $id_anggota, {$i['jumlah']}, {$i['harga']},
         '$metode', 'menunggu', 'penjualan', NOW())
    ");
}

header("Location: transaksi_barang.php");
exit;
