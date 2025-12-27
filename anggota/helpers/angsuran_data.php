<?php
if (!isset($conn) || !isset($id_user)) {
    die('Akses tidak valid');
}

/* Ambil id_anggota */
$qAnggota = mysqli_query($conn, "
    SELECT id_anggota
    FROM anggota
    WHERE id_user = '$id_user'
");

$dataAnggota = mysqli_fetch_assoc($qAnggota);
$id_anggota  = $dataAnggota['id_anggota'] ?? 0;

/* Jika anggota tidak ditemukan */
if (!$id_anggota) {
    $qAngsuran = false;
    return;
}

/* Ambil riwayat angsuran */
$qAngsuran = mysqli_query($conn, "
    SELECT
        a.tanggal_bayar,
        a.angsuran_ke,
        a.jumlah_bayar,
        a.keterangan,
        p.jumlah_pinjaman,
        p.tenor
    FROM angsuran a
    JOIN pengajuan_pinjaman p
        ON a.id_pengajuan = p.id_pengajuan
    WHERE p.id_anggota = '$id_anggota'
    ORDER BY a.tanggal_bayar DESC
");
