<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   DEFAULT AMAN
================================ */
$totalPinjamanAktif = 0;
$dataPinjaman = [];

/* ===============================
   TOTAL PINJAMAN AKTIF
   (STATUS DISETUJUI)
================================ */
$qTotal = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_pinjaman), 0) AS total
    FROM pengajuan_pinjaman
    WHERE status = 'berjalan'
");

if ($qTotal) {
    $row = mysqli_fetch_assoc($qTotal);
    $totalPinjamanAktif = (float) $row['total'];
}

/* ===============================
   DATA PINJAMAN (DISSETUJUI)
================================ */
$qData = mysqli_query($conn, "
    SELECT
        p.id_pengajuan,
        u.nama,
        p.jumlah_pinjaman,
        p.tenor,
        p.cicilan,
        p.status,
        p.tanggal_pengajuan
    FROM pengajuan_pinjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    WHERE p.status = 'berjalan'
    ORDER BY p.tanggal_pengajuan DESC
");

if ($qData) {
    while ($row = mysqli_fetch_assoc($qData)) {
        $dataPinjaman[] = $row;
    }
}
