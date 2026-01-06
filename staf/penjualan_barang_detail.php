<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id = (int) $_GET['id'];

/* AMBIL DATA TRANSAKSI */
$query = "
    SELECT 
        t.id_transaksi,
        t.tanggal_transaksi,
        t.jumlah,
        t.harga,
        (t.jumlah * t.harga) AS total,
        t.metode_pembayaran,
        b.nama_barang,
        u.nama AS nama_anggota
    FROM transaksi_barang t
    LEFT JOIN barang b ON t.id_barang = b.id_barang
    LEFT JOIN anggota a ON t.id_anggota = a.id_anggota
    LEFT JOIN users u ON a.id_user = u.id_user
    WHERE t.id_transaksi = $id
";

$data = mysqli_query($conn, $query);
$trx  = mysqli_fetch_assoc($data);

if (!$trx) {
    die("Data transaksi tidak ditemukan");
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-3">Detail Transaksi Penjualan</h4>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered">
<tr>
    <th width="30%">Tanggal</th>
    <td><?= date('d M Y', strtotime($trx['tanggal_transaksi'])); ?></td>
</tr>
<tr>
    <th>Anggota</th>
    <td><?= $trx['nama_anggota'] ?? 'Umum'; ?></td>
</tr>
<tr>
    <th>Barang</th>
    <td><?= $trx['nama_barang']; ?></td>
</tr>
<tr>
    <th>Jumlah</th>
    <td><?= $trx['jumlah']; ?></td>
</tr>
<tr>
    <th>Harga Satuan</th>
    <td>Rp <?= number_format($trx['harga'],0,',','.'); ?></td>
</tr>
<tr>
    <th>Total</th>
    <td><strong>Rp <?= number_format($trx['total'],0,',','.'); ?></strong></td>
</tr>
<tr>
    <th>Metode Pembayaran</th>
    <td>
        <span class="badge <?= $trx['metode_pembayaran']=='simpanan'?'bg-warning':'bg-success'; ?>">
            <?= ucfirst($trx['metode_pembayaran']); ?>
        </span>
    </td>
</tr>
</table>

<a href="penjualan_barang.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

</div>
</div>

<?php include 'layout/footer.php'; ?>
