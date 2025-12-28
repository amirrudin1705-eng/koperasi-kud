<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   FILTER BULAN & TAHUN
================================ */
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$whereTanggal = '';
if ($bulan && $tahun) {
    $whereTanggal = "AND MONTH(p.tanggal_pengajuan) = '$bulan'
                     AND YEAR(p.tanggal_pengajuan) = '$tahun'";
} elseif ($tahun) {
    $whereTanggal = "AND YEAR(p.tanggal_pengajuan) = '$tahun'";
}

/* ===============================
   RINGKASAN LAPORAN
================================ */

// Total Simpanan
$qSimpanan = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah),0) AS total
    FROM simpanan
");
$totalSimpanan = mysqli_fetch_assoc($qSimpanan)['total'] ?? 0;

// Total Pinjaman Aktif
$qPinjaman = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_pinjaman),0) AS total
    FROM pengajuan_pinjaman
    WHERE status = 'disetujui'
");
$totalPinjamanAktif = mysqli_fetch_assoc($qPinjaman)['total'] ?? 0;

// Total Angsuran
$qAngsuran = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar),0) AS total
    FROM angsuran
");
$totalAngsuranMasuk = mysqli_fetch_assoc($qAngsuran)['total'] ?? 0;

// Total Tunggakan (global)
$totalTunggakan = max(
    $totalPinjamanAktif - $totalAngsuranMasuk,
    0
);

/* ===============================
   DATA TUNGGAKAN PER ANGGOTA
================================ */
$dataTunggakan = [];

$queryTunggakan = "
SELECT
    u.nama,
    p.id_pengajuan,
    p.jumlah_pinjaman,
    COALESCE(SUM(a.jumlah_bayar), 0) AS total_bayar,
    (p.jumlah_pinjaman - COALESCE(SUM(a.jumlah_bayar), 0)) AS tunggakan
FROM pengajuan_pinjaman p
JOIN anggota ag ON p.id_anggota = ag.id_anggota
JOIN users u ON ag.id_user = u.id_user
LEFT JOIN angsuran a ON p.id_pengajuan = a.id_pengajuan
WHERE p.status = 'disetujui'
$whereTanggal
GROUP BY p.id_pengajuan
HAVING tunggakan > 0
ORDER BY tunggakan DESC
";

$qTunggakan = mysqli_query($conn, $queryTunggakan);

if ($qTunggakan) {
    while ($row = mysqli_fetch_assoc($qTunggakan)) {
        $dataTunggakan[] = $row;
    }
}
