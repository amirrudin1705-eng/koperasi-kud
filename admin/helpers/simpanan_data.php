<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   DEFAULT AMAN
================================ */
$totalSimpanan = 0;
$dataSimpanan  = [];

/* ===============================
   TOTAL SIMPANAN
================================ */
$qTotal = mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah), 0) AS total_simpanan
    FROM simpanan
");

if ($qTotal) {
    $row = mysqli_fetch_assoc($qTotal);
    $totalSimpanan = (float) $row['total_simpanan'];
}

/* ===============================
   DATA SIMPANAN DETAIL
================================ */
$qData = mysqli_query($conn, "
    SELECT
        s.id_simpanan,
        u.nama,
        a.nomor_anggota,
        s.jenis_simpanan,
        s.jumlah,
        s.tanggal
    FROM simpanan s
    JOIN anggota a ON s.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    ORDER BY s.tanggal DESC
");

if ($qData) {
    while ($row = mysqli_fetch_assoc($qData)) {
        $dataSimpanan[] = $row;
    }
}
