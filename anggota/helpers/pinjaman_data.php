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

$qRingkasan = mysqli_query($conn, "
    SELECT
        COALESCE(SUM(jumlah_pinjaman), 0) AS total_pinjaman,
        COALESCE(SUM(jumlah_pinjaman - cicilan), 0) AS sisa_angsuran
    FROM simpan_pinjam
    WHERE id_anggota = '$id_anggota'
      AND status_pinjaman = 'berjalan'
");

$dataRingkasan = mysqli_fetch_assoc($qRingkasan);

$totalPinjaman = $dataRingkasan['total_pinjaman'];
$sisaAngsuran  = $dataRingkasan['sisa_angsuran'];

$qRiwayat = mysqli_query($conn, "
    SELECT
        id_pinjaman,
        tanggal_pinjaman,
        jumlah_pinjaman,
        cicilan,
        status_pinjaman
    FROM simpan_pinjam
    WHERE id_anggota = '$id_anggota'
    ORDER BY tanggal_pinjaman DESC
");
