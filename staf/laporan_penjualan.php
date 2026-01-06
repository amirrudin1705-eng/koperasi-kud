<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

/* QUERY LAPORAN */
$query = "
    SELECT 
        t.id_transaksi,
        t.tanggal_transaksi,
        b.nama_barang,
        b.satuan,
        t.jumlah,
        t.harga,
        (t.jumlah * t.harga) AS total,
        t.metode_pembayaran
    FROM transaksi_barang t
    JOIN barang b ON t.id_barang = b.id_barang
    WHERE MONTH(t.tanggal_transaksi) = '$bulan'
      AND YEAR(t.tanggal_transaksi) = '$tahun'
    ORDER BY t.tanggal_transaksi DESC
";

$data = mysqli_query($conn, $query);

/* SIMPAN DATA */
$rows = [];
$totalPenjualan = 0;
while ($r = mysqli_fetch_assoc($data)) {
    $rows[] = $r;
    $totalPenjualan += $r['total'];
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-3">Laporan Penjualan</h4>

<form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">Bulan</label>
        <select name="bulan" class="form-select">
            <?php for ($i=1; $i<=12; $i++): 
                $val = sprintf('%02d',$i); ?>
                <option value="<?= $val ?>" <?= $bulan==$val?'selected':'' ?>>
                    <?= date('F', mktime(0,0,0,$i,1)) ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Tahun</label>
        <select name="tahun" class="form-select">
            <?php for ($y=date('Y'); $y>=2022; $y--): ?>
                <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-md-3 align-self-end">
        <button class="btn btn-primary">
            <i class="bi bi-filter"></i> Tampilkan
        </button>
    </div>
</form>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Pembayaran</th>
    <th width="10%">Aksi</th>
</tr>
</thead>
<tbody>

<?php if (!empty($rows)): ?>
<?php $no=1; foreach ($rows as $r): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d M Y', strtotime($r['tanggal_transaksi'])) ?></td>
    <td><?= htmlspecialchars($r['nama_barang']) ?></td>
    <td><?= $r['jumlah'].' '.$r['satuan'] ?></td>
    <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
    <td><?= ucfirst($r['metode_pembayaran']) ?></td>
    <td>
        <button class="btn btn-sm btn-info"
            data-bs-toggle="modal"
            data-bs-target="#detail<?= $r['id_transaksi'] ?>">
            Detail
        </button>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="7" class="text-center text-muted">Tidak ada data</td>
</tr>
<?php endif; ?>

</tbody>
<tfoot>
<tr class="table-light">
    <th colspan="4" class="text-end">TOTAL</th>
    <th colspan="3">
        Rp <?= number_format($totalPenjualan,0,',','.') ?>
    </th>
</tr>
</tfoot>
</table>

</div>
</div>

<!-- ================= MODAL DETAIL (DI LUAR TABEL) ================= -->
<?php foreach ($rows as $r): ?>
<div class="modal fade" id="detail<?= $r['id_transaksi'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Detail Transaksi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<table class="table table-bordered">
<tr>
    <th>Tanggal</th>
    <td><?= date('d M Y', strtotime($r['tanggal_transaksi'])) ?></td>
</tr>
<tr>
    <th>Barang</th>
    <td><?= htmlspecialchars($r['nama_barang']) ?></td>
</tr>
<tr>
    <th>Jumlah</th>
    <td><?= $r['jumlah'].' '.$r['satuan'] ?></td>
</tr>
<tr>
    <th>Harga Satuan</th>
    <td>Rp <?= number_format($r['harga'],0,',','.') ?></td>
</tr>
<tr>
    <th>Total</th>
    <td><strong>Rp <?= number_format($r['total'],0,',','.') ?></strong></td>
</tr>
<tr>
    <th>Metode Pembayaran</th>
    <td><?= ucfirst($r['metode_pembayaran']) ?></td>
</tr>
</table>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>

</div>
</div>
</div>
<?php endforeach; ?>

<?php include 'layout/footer.php'; ?>
