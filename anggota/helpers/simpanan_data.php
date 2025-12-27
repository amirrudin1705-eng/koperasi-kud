<?php
if (!isset($conn) || !isset($id_user)) {
    die('Akses tidak valid');
}

$qAnggota = mysqli_query($conn, "
    SELECT id_anggota
    FROM anggota
    WHERE id_user = '$id_user'
");

$dataAnggota = mysqli_fetch_assoc($qAnggota);
$id_anggota  = $dataAnggota['id_anggota'] ?? 0;

$qTotal = mysqli_query($conn, "
    SELECT
        SUM(CASE WHEN jenis_simpanan = 'pokok' THEN jumlah ELSE 0 END) AS total_pokok,
        SUM(CASE WHEN jenis_simpanan = 'wajib' THEN jumlah ELSE 0 END) AS total_wajib
    FROM simpanan
    WHERE id_anggota = '$id_anggota'
");

$dataTotal = mysqli_fetch_assoc($qTotal);

$totalPokok = $dataTotal['total_pokok'] ?? 0;
$totalWajib = $dataTotal['total_wajib'] ?? 0;

$totalSimpanan = $totalPokok + $totalWajib;

$qRiwayat = mysqli_query($conn, "
    SELECT
        tanggal,
        jenis_simpanan,
        jumlah,
        keterangan
    FROM simpanan
    WHERE id_anggota = '$id_anggota'
    ORDER BY tanggal DESC
");
