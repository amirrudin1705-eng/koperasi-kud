<?php
/******************************************
 * DASHBOARD ADMIN - DATA HELPER (FIX FINAL)
 ******************************************/

if (!isset($conn)) {
    require_once __DIR__ . '/../../config/database.php';
}

/* ==============================
   TOTAL ANGGOTA
   (SEMUA ROLE ANGGOTA)
================================ */
$qAnggota = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'anggota'"
);
$totalAnggota = mysqli_fetch_assoc($qAnggota)['total'] ?? 0;


/* ==============================
   TOTAL SIMPANAN
================================ */
$qSimpanan = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(jumlah), 0) AS total
     FROM simpanan"
);
$totalSimpanan = mysqli_fetch_assoc($qSimpanan)['total'] ?? 0;


/* ==============================
   TOTAL PINJAMAN AKTIF
   (STATUS DISETUJUI)
================================ */
$qPinjaman = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(jumlah_pinjaman), 0) AS total
     FROM pengajuan_pinjaman
     WHERE status = 'berjalan'"
);
$totalPinjaman = mysqli_fetch_assoc($qPinjaman)['total'] ?? 0;


/* ==============================
   TOTAL PINJAMAN BERMASALAH
   (BELUM LUNAS)
================================ */
$qTunggakan = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM pengajuan_pinjaman p
     LEFT JOIN (
        SELECT 
            id_pengajuan,
            COALESCE(SUM(jumlah_bayar), 0) AS total_bayar
        FROM angsuran
        GROUP BY id_pengajuan
     ) a ON p.id_pengajuan = a.id_pengajuan
     WHERE p.status = 'berjalan'
       AND COALESCE(a.total_bayar, 0) < p.jumlah_pinjaman"
);

$totalTunggakan = mysqli_fetch_assoc($qTunggakan)['total'] ?? 0;
