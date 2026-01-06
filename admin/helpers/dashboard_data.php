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
   PINJAMAN
   (BELUM DISENTUH, AMAN)
================================ */
$totalPinjaman  = 0;
$totalTunggakan = 0;


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
