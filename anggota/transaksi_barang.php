<?php
session_start();
require_once '../config/database.php';

/* PROTEKSI LOGIN */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = (int)$_SESSION['id_user'];

/* DATA USER */
$user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT nama, foto FROM users WHERE id_user=$id_user
"));

$namaUser   = $user['nama'] ?? 'Anggota';
$fotoProfil = !empty($user['foto'])
    ? "../assets/uploads/profile/".$user['foto']
    : "../assets/uploads/profile/default.png";

/* ID ANGGOTA */
$a = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id_anggota FROM anggota WHERE id_user=$id_user
"));
$id_anggota = (int)($a['id_anggota'] ?? 0);
if (!$id_anggota) die("Anggota tidak ditemukan");

/* ===============================
 * DATA BARANG (HANYA AKTIF)
 * =============================== */
$barang = mysqli_query($conn,"
    SELECT id_barang, nama_barang, stok, satuan, harga_jual
    FROM barang
    WHERE is_active = 1
      AND stok > 0
    ORDER BY nama_barang
");

/* ===============================
 * RIWAYAT TRANSAKSI ANGGOTA
 * =============================== */
$riwayat = mysqli_query($conn,"
    SELECT 
        t.tanggal_transaksi,
        t.jumlah,
        t.harga,
        t.status,
        b.nama_barang,
        b.satuan
    FROM transaksi_barang t
    JOIN barang b ON t.id_barang=b.id_barang
    WHERE t.id_anggota=$id_anggota
    ORDER BY t.tanggal_transaksi DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaksi Barang</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="d-flex min-vh-100">

<!-- SIDEBAR -->
<aside class="sidebar p-3">
  <h5 class="fw-bold text-center mb-4">KUD Simpan Pinjam</h5>
  <ul class="nav flex-column gap-1">
    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="profil.php"><i class="bi bi-person"></i> Profil Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="simpanan.php"><i class="bi bi-wallet2"></i> Simpanan Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="pinjaman.php"><i class="bi bi-file-text"></i> Pinjaman Saya</a></li>
    <li class="nav-item"><a class="nav-link" href="ajukan_pinjaman.php"><i class="bi bi-pencil-square"></i> Ajukan Pinjaman</a></li>
    <li class="nav-item"><a class="nav-link " href="angsuran.php"><i class="bi bi-clock-history"></i> Riwayat Angsuran</a></li>
    <li class="nav-item"><a class="nav-link active" href="transaksi_barang.php"><i class="bi bi-cart"></i> Transaksi Barang</a></li>
    <hr>
    <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<main class="flex-fill bg-light">

<!-- TOPBAR -->
<div class="d-flex justify-content-end align-items-center p-3 bg-white shadow-sm">
<div class="text-end me-3">
<div class="fw-semibold"><?= htmlspecialchars($namaUser) ?></div>
<small class="text-muted">Anggota</small>
</div>
<img src="<?= $fotoProfil ?>" class="rounded-circle" width="42" height="42">
</div>

<div class="container-fluid p-4">

<h4 class="fw-bold mb-3">Transaksi Barang</h4>

<!-- FORM TRANSAKSI -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<h6 class="fw-bold mb-3">Ajukan Pembelian</h6>

<form action="transaksi_barang_simpan.php" method="post" id="formTransaksi">

<div id="listBarang"></div>

<button type="button" class="btn btn-outline-primary mb-3" onclick="tambahBarang()">
<i class="bi bi-plus-circle"></i> Tambah Barang
</button>

<div class="mb-3">
<label class="form-label">Total Keseluruhan</label>
<input type="text" id="grandTotal" class="form-control fw-bold" readonly>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Metode Pembayaran</label>
    <select name="metode_pembayaran" class="form-select" required>
        <option value="">-- Pilih Metode Pembayaran --</option>
        <option value="cash">Cash</option>
        <option value="transfer">Transfer</option>
        <option value="simpanan">Potong Simpanan</option>
    </select>
</div>

<button class="btn btn-primary">
<i class="bi bi-send"></i> Ajukan Transaksi
</button>

</form>
</div>
</div>

<!-- RIWAYAT -->
<div class="card shadow-sm">
<div class="card-body">
<h6 class="fw-bold mb-3">Riwayat Transaksi</h6>

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Barang</th>
<th>Jumlah</th>
<th>Harga</th>
<th>Total</th>
<th>Status</th>
</tr>
</thead>
<tbody>

<?php $no=1; while($r=mysqli_fetch_assoc($riwayat)): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= date('d M Y',strtotime($r['tanggal_transaksi'])) ?></td>
<td><?= htmlspecialchars($r['nama_barang']) ?></td>
<td><?= $r['jumlah'].' '.$r['satuan'] ?></td>
<td>Rp <?= number_format($r['harga'],0,',','.') ?>/<?= $r['satuan'] ?></td>
<td>Rp <?= number_format($r['jumlah']*$r['harga'],0,',','.') ?></td>
<td>
<span class="badge 
<?= $r['status']=='disetujui'?'bg-success':($r['status']=='ditolak'?'bg-danger':'bg-warning') ?>">
<?= ucfirst($r['status']) ?>
</span>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>
</div>

</div>
</main>
</div>

<script>
let index = 0;

function tambahBarang(){
index++;
const html = `
<div class="row g-3 mb-2 border rounded p-3">
<div class="col-md-5">
<select name="barang[${index}][id]" class="form-select barang" onchange="updateHarga(this)" required>
<option value="">-- Pilih Barang --</option>
<?php mysqli_data_seek($barang,0); while($b=mysqli_fetch_assoc($barang)): ?>
<option value="<?= $b['id_barang'] ?>"
data-harga="<?= $b['harga_jual'] ?>"
data-satuan="<?= $b['satuan'] ?>">
<?= htmlspecialchars($b['nama_barang']) ?> (<?= $b['stok'].' '.$b['satuan'] ?>)
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2">
<input type="number" name="barang[${index}][jumlah]" class="form-control jumlah" min="1"
oninput="hitungTotal()" required>
</div>

<div class="col-md-2">
<input type="text" class="form-control harga" readonly>
</div>

<div class="col-md-2">
<input type="text" class="form-control total" readonly>
</div>
</div>`;
document.getElementById('listBarang').insertAdjacentHTML('beforeend', html);
}

function hitungTotal(){
let grand = 0;
document.querySelectorAll('.row.g-3').forEach(row=>{
const harga = row.querySelector('.barang')?.selectedOptions[0]?.dataset.harga || 0;
const qty   = row.querySelector('.jumlah')?.value || 0;
const total = harga * qty;
if(row.querySelector('.total')) row.querySelector('.total').value = total.toLocaleString('id-ID');
grand += total;
});
document.getElementById('grandTotal').value = grand.toLocaleString('id-ID');
}

function updateHarga(el){
const row = el.closest('.row');
row.querySelector('.harga').value =
"Rp "+parseInt(el.selectedOptions[0].dataset.harga).toLocaleString('id-ID')+
" / "+el.selectedOptions[0].dataset.satuan;
hitungTotal();
}

tambahBarang();
</script>

</body>
</html>
