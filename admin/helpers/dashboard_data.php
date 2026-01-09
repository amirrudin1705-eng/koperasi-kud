<?php
/*************************************************
 * DASHBOARD DATA - ADMIN KUD (FINAL & VALID)
 * DISUSUN BERDASARKAN simpanan.php (REAL DB)
 *************************************************/

/* ===============================
   TOTAL ANGGOTA
================================ */
$qAnggota = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM anggota
");
$totalAnggota = mysqli_fetch_assoc($qAnggota)['total'] ?? 0;


/* ===============================
   TOTAL SIMPANAN
   (SESUAI simpanan.php)
================================ */
$qSimpanan = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM simpanan
");
$totalSimpanan = mysqli_fetch_assoc($qSimpanan)['total'] ?? 0;


/* ===============================
 * TOTAL PINJAMAN
 * =============================== */
$qPinjaman = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_pinjaman), 0) AS total
    FROM pengajuan_pinjaman
    WHERE status IN ('berjalan', 'lunas')
");

$rowPinjaman = mysqli_fetch_assoc($qPinjaman);
$totalPinjaman = $rowPinjaman['total'];

$qAnggotaMenunggak = mysqli_query($conn, "
    SELECT COUNT(DISTINCT p.id_anggota) AS total
    FROM pengajuan_pinjaman p
    LEFT JOIN (
        SELECT id_pengajuan, SUM(jumlah_bayar) AS total_bayar
        FROM angsuran
        GROUP BY id_pengajuan
    ) a ON p.id_pengajuan = a.id_pengajuan
    WHERE p.status = 'berjalan'
      AND (p.jumlah_pinjaman - COALESCE(a.total_bayar, 0)) > 0
");

$totalAnggotaMenunggak = mysqli_fetch_assoc($qAnggotaMenunggak)['total'] ?? 0;




/* ===============================
   DATA BARANG
================================ */
$qBarang = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM barang
");
$totalBarang = mysqli_fetch_assoc($qBarang)['total'] ?? 0;

$qStok = mysqli_query($conn, "
    SELECT SUM(stok) AS total
    FROM barang
");
$totalStokBarang = mysqli_fetch_assoc($qStok)['total'] ?? 0;
