<?php
if (!isset($conn)) {
    die("Database tidak terhubung");
}

/* ===============================
 * FILTER BULAN & TAHUN
 * =============================== */
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

/* ===============================
 * TOTAL SIMPANAN
 * =============================== */
$qSimpanan = "
    SELECT COALESCE(SUM(jumlah),0) AS total
    FROM simpanan
    WHERE 1=1
";
if ($tahun !== '') {
    $qSimpanan .= " AND YEAR(tanggal) = '$tahun'";
}
if ($bulan !== '') {
    $qSimpanan .= " AND MONTH(tanggal) = '$bulan'";
}
$res = mysqli_query($conn, $qSimpanan);
$totalSimpanan = mysqli_fetch_assoc($res)['total'] ?? 0;

/* ===============================
 * TOTAL ANGSURAN MASUK
 * =============================== */
$qAngsuran = "
    SELECT COALESCE(SUM(jumlah_bayar),0) AS total
    FROM angsuran
    WHERE 1=1
";
if ($tahun !== '') {
    $qAngsuran .= " AND YEAR(tanggal_bayar) = '$tahun'";
}
if ($bulan !== '') {
    $qAngsuran .= " AND MONTH(tanggal_bayar) = '$bulan'";
}
$res = mysqli_query($conn, $qAngsuran);
$totalAngsuranMasuk = mysqli_fetch_assoc($res)['total'] ?? 0;

/* ===============================
 * TOTAL PENJUALAN BARANG
 * =============================== */
$qPenjualan = "
    SELECT COALESCE(SUM(jumlah * harga),0) AS total
    FROM transaksi_barang
    WHERE jenis_transaksi = 'pembelian'
";
if ($tahun !== '') {
    $qPenjualan .= " AND YEAR(tanggal_transaksi) = '$tahun'";
}
if ($bulan !== '') {
    $qPenjualan .= " AND MONTH(tanggal_transaksi) = '$bulan'";
}
$res = mysqli_query($conn, $qPenjualan);
$totalPenjualanBarang = mysqli_fetch_assoc($res)['total'] ?? 0;

/* ===============================
 * TOTAL DANA MASUK
 * =============================== */
$totalDanaMasuk =
    $totalSimpanan +
    $totalAngsuranMasuk +
    $totalPenjualanBarang;

/* ===============================
 * DATA TUNGGAKAN PER ANGGOTA
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
$res = mysqli_query($conn, $qTunggakan);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $dataTunggakan[] = $row;
    }
}

/* ===============================
 * DETAIL SIMPANAN
 * =============================== */
$qDetailSimpanan = "
    SELECT 
        s.tanggal,
        u.nama AS nama_anggota,
        s.jumlah
    FROM simpanan s
    JOIN anggota a ON s.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    WHERE 1=1
";
if ($tahun !== '') {
    $qDetailSimpanan .= " AND YEAR(s.tanggal) = '$tahun'";
}
if ($bulan !== '') {
    $qDetailSimpanan .= " AND MONTH(s.tanggal) = '$bulan'";
}
$qDetailSimpanan .= " ORDER BY s.tanggal DESC";
$detailSimpanan = mysqli_query($conn, $qDetailSimpanan) ?: false;

/* ===============================
 * DETAIL ANGSURAN
 * =============================== */
$qDetailAngsuran = "
    SELECT 
        an.tanggal_bayar,
        u.nama AS nama_anggota,
        an.jumlah_bayar
    FROM angsuran an
    JOIN pengajuan_pinjaman p ON an.id_pengajuan = p.id_pengajuan
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    WHERE 1=1
";
if ($tahun !== '') {
    $qDetailAngsuran .= " AND YEAR(an.tanggal_bayar) = '$tahun'";
}
if ($bulan !== '') {
    $qDetailAngsuran .= " AND MONTH(an.tanggal_bayar) = '$bulan'";
}
$qDetailAngsuran .= " ORDER BY an.tanggal_bayar DESC";
$detailAngsuran = mysqli_query($conn, $qDetailAngsuran) ?: false;

/* ===============================
 * DETAIL PENJUALAN BARANG
 * =============================== */
$qDetailPenjualan = "
    SELECT 
        t.tanggal_transaksi,
        u.nama AS nama_anggota,
        b.nama_barang,
        b.satuan,
        t.jumlah,
        t.harga,
        (t.jumlah * t.harga) AS total,
        t.metode_pembayaran,
        t.status
    FROM transaksi_barang t
    JOIN barang b ON t.id_barang = b.id_barang
    LEFT JOIN anggota a ON t.id_anggota = a.id_anggota
    LEFT JOIN users u ON a.id_user = u.id_user
    WHERE t.jenis_transaksi = 'pembelian'
";
if ($tahun !== '') {
    $qDetailPenjualan .= " AND YEAR(t.tanggal_transaksi) = '$tahun'";
}
if ($bulan !== '') {
    $qDetailPenjualan .= " AND MONTH(t.tanggal_transaksi) = '$bulan'";
}
$qDetailPenjualan .= " ORDER BY t.tanggal_transaksi DESC";
$detailPenjualan = mysqli_query($conn, $qDetailPenjualan) ?: false;
