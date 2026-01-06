<?php
if (!isset($conn)) {
    die("Database tidak terhubung");
}

/* ===============================
 * FILTER BULAN & TAHUN
 * =============================== */
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? date('Y');

/* ===============================
 * TOTAL SIMPANAN
 * =============================== */
$qSimpanan = "
    SELECT COALESCE(SUM(jumlah),0) AS total
    FROM simpanan
    WHERE YEAR(tanggal) = '$tahun'
";
if ($bulan != '') {
    $qSimpanan .= " AND MONTH(tanggal) = '$bulan'";
}
$totalSimpanan = mysqli_fetch_assoc(mysqli_query($conn, $qSimpanan))['total'];

/* ===============================
 * TOTAL ANGSURAN MASUK
 * =============================== */
$qAngsuran = "
    SELECT COALESCE(SUM(jumlah_bayar),0) AS total
    FROM angsuran
    WHERE YEAR(tanggal_bayar) = '$tahun'
";
if ($bulan != '') {
    $qAngsuran .= " AND MONTH(tanggal_bayar) = '$bulan'";
}
$totalAngsuranMasuk = mysqli_fetch_assoc(mysqli_query($conn, $qAngsuran))['total'];

/* ===============================
 * TOTAL PENJUALAN BARANG
 * =============================== */
$qPenjualan = "
    SELECT COALESCE(SUM(jumlah * harga),0) AS total
    FROM transaksi_barang
    WHERE YEAR(tanggal_transaksi) = '$tahun'
";
if ($bulan != '') {
    $qPenjualan .= " AND MONTH(tanggal_transaksi) = '$bulan'";
}
$totalPenjualanBarang = mysqli_fetch_assoc(mysqli_query($conn, $qPenjualan))['total'];

/* ===============================
 * DATA TUNGGAKAN PER ANGGOTA
 * (TIDAK TERPENGARUH FILTER)
 * =============================== */
$dataTunggakan = [];

$qTunggakan = "
    SELECT 
        u.nama AS nama_anggota,
        p.jumlah_pinjaman,
        COALESCE(SUM(an.jumlah_bayar),0) AS total_bayar,
        (p.jumlah_pinjaman - COALESCE(SUM(an.jumlah_bayar),0)) AS tunggakan
    FROM pengajuan_pinjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    LEFT JOIN angsuran an ON p.id_pengajuan = an.id_pengajuan
    WHERE p.status = 'disetujui'
    GROUP BY p.id_pengajuan
    HAVING tunggakan > 0
";

$resTunggakan = mysqli_query($conn, $qTunggakan);
while ($r = mysqli_fetch_assoc($resTunggakan)) {
    $dataTunggakan[] = $r;
}

/* ===============================
 * DETAIL PENJUALAN BARANG
 * =============================== */
$qDetailPenjualan = "
    SELECT 
        t.tanggal_transaksi,
        b.nama_barang,
        b.satuan,
        t.jumlah,
        t.harga,
        (t.jumlah * t.harga) AS total,
        t.metode_pembayaran
    FROM transaksi_barang t
    JOIN barang b ON t.id_barang = b.id_barang
    WHERE YEAR(t.tanggal_transaksi) = '$tahun'
";
if ($bulan != '') {
    $qDetailPenjualan .= " AND MONTH(t.tanggal_transaksi) = '$bulan'";
}
$qDetailPenjualan .= " ORDER BY t.tanggal_transaksi DESC";

$dataPenjualanBarang = mysqli_query($conn, $qDetailPenjualan);
