<?php
$penjualan = [];

$query = "
    SELECT 
        t.tanggal_transaksi,
        (t.jumlah * t.harga) AS total,
        t.metode_pembayaran,
        u.nama AS nama_anggota,
        b.nama_barang
    FROM transaksi_barang t
    LEFT JOIN anggota a ON t.id_anggota = a.id_anggota
    LEFT JOIN users u ON a.id_user = u.id_user
    LEFT JOIN barang b ON t.id_barang = b.id_barang
    WHERE t.jenis_transaksi = 'penjualan'
    ORDER BY t.tanggal_transaksi DESC
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $penjualan[] = $row;
}
