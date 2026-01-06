<?php
require '../auth/auth_staf.php';
require '../config/database.php';

/* DATA PENJUALAN */
$penjualan = mysqli_query($conn, "
    SELECT 
        t.id_transaksi,
        t.tanggal_transaksi,
        t.jumlah,
        t.harga,
        (t.jumlah * t.harga) AS total,
        t.status,
        t.metode_pembayaran,
        u.nama AS nama_anggota,
        b.nama_barang,
        b.satuan
    FROM transaksi_barang t
    LEFT JOIN anggota a ON t.id_anggota = a.id_anggota
    LEFT JOIN users u ON a.id_user = u.id_user
    JOIN barang b ON t.id_barang = b.id_barang
    WHERE t.jenis_transaksi = 'penjualan'
    ORDER BY t.tanggal_transaksi DESC
");

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-1">Penjualan Barang</h4>
<p class="text-muted mb-4">
    Daftar transaksi penjualan barang koperasi (termasuk verifikasi)
</p>

<?php if (isset($_GET['status']) && $_GET['status']=='success'): ?>
<div class="alert alert-success">Transaksi berhasil diproses</div>
<?php endif; ?>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Anggota</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php if ($penjualan && mysqli_num_rows($penjualan) > 0): ?>
<?php $no=1; while($row=mysqli_fetch_assoc($penjualan)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= date('d M Y', strtotime($row['tanggal_transaksi'])); ?></td>
    <td><?= htmlspecialchars($row['nama_anggota'] ?? 'Umum'); ?></td>
    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
    <td><?= $row['jumlah'].' '.$row['satuan']; ?></td>
    <td>Rp <?= number_format($row['total'],0,',','.'); ?></td>
    <td>
        <?php
        if ($row['status']=='menunggu')
            echo '<span class="badge bg-warning">Menunggu</span>';
        elseif ($row['status']=='disetujui')
            echo '<span class="badge bg-success">Disetujui</span>';
        else
            echo '<span class="badge bg-danger">Ditolak</span>';
        ?>
    </td>
    <td>
        <?php if ($row['status']=='menunggu'): ?>
            <a href="penjualan_barang_verif.php?id=<?= $row['id_transaksi'] ?>&aksi=setujui"
               class="btn btn-success btn-sm"
               onclick="return confirm('Setujui transaksi ini?')">
               Setujui
            </a>
            <a href="penjualan_barang_verif.php?id=<?= $row['id_transaksi'] ?>&aksi=tolak"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Tolak transaksi ini?')">
               Tolak
            </a>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="8" class="text-center text-muted">
        Belum ada transaksi penjualan
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>

<?php include 'layout/footer.php'; ?>
