<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ambil id anggota */
$q = mysqli_query($conn, "
    SELECT id_anggota FROM anggota WHERE id_user = '$id_user'
");
$id_anggota = mysqli_fetch_assoc($q)['id_anggota'] ?? 0;

if (!$id_anggota) {
    die('Anggota tidak valid');
}

/* ambil bunga */
$qBunga = mysqli_query($conn, "SELECT bunga_default FROM pengaturan LIMIT 1");
$bunga = (float)(mysqli_fetch_assoc($qBunga)['bunga_default'] ?? 0);

/* SIMULASI */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'simulasi') {

    $jumlah = preg_replace('/[^0-9]/', '', $_POST['jumlah_pinjaman'] ?? '');
    $jumlah = (float) $jumlah;


    if ($jumlah <= 0) {
        $_SESSION['error'] = 'Jumlah pinjaman tidak valid';
        header("Location: ../ajukan_pinjaman.php");
        exit;
    }

    $hasil = [];
    foreach ([3,6,12] as $tenor) {
        $bunga_per_bulan = ($bunga / 100) * $jumlah;
        $total = $jumlah + ($bunga_per_bulan * $tenor);
        $hasil[] = [
            'tenor' => $tenor,
            'cicilan' => round($total / $tenor)
        ];
    }

    $_SESSION['simulasi'] = [
        'jumlah' => $jumlah,
        'bunga' => $bunga,
        'hasil' => $hasil
    ];

    header("Location: ../ajukan_pinjaman.php");
    exit;
}

/* AJUKAN PINJAMAN */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'ajukan') {

    $jumlah = (float)$_POST['jumlah'];
    $tenor  = (int)$_POST['tenor'];

    if (!$jumlah || !$tenor) {
        die('Data tidak lengkap');
    }

    /* cek pinjaman aktif */
    $cek = mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM pengajuan_pinjaman
        WHERE id_anggota = '$id_anggota'
          AND status = 'berjalan'
    ");
    if ((mysqli_fetch_assoc($cek)['total'] ?? 0) > 0) {
        die('Masih ada pinjaman aktif');
    }

    $bunga_per_bulan = ($bunga / 100) * $jumlah;
    $total = $jumlah + ($bunga_per_bulan * $tenor);
    $cicilan = $total / $tenor;

    mysqli_query($conn, "
        INSERT INTO pengajuan_pinjaman
        (id_anggota, tanggal_pengajuan, jumlah_pinjaman, tenor, bunga, cicilan, status)
        VALUES
        ('$id_anggota', CURDATE(), '$jumlah', '$tenor', '$bunga', '$cicilan', 'menunggu')
    ");

    unset($_SESSION['simulasi']);
    header("Location: ../pinjaman.php");
    exit;
}
