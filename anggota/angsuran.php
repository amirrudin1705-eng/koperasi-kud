<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* Ambil id_anggota */
$qAnggota = mysqli_query($conn, "
    SELECT id_anggota 
    FROM anggota 
    WHERE id_user = '$id_user'
");
$dataAnggota = mysqli_fetch_assoc($qAnggota);
$id_anggota  = $dataAnggota['id_anggota'] ?? 0;

if (!$id_anggota) {
    echo "<script>
        alert('Data keanggotaan tidak ditemukan.');
        window.location='dashboard.php';
    </script>";
    exit;
}

/* Ambil riwayat angsuran */
$qAngsuran = mysqli_query($conn, "
    SELECT 
        a.tanggal_bayar,
        a.angsuran_ke,
        a.jumlah_bayar,
        a.keterangan,
        p.jumlah_pinjaman,
        p.tenor
    FROM angsuran a
    JOIN pengajuan_pinjaman p 
        ON a.id_pengajuan = p.id_pengajuan
    WHERE p.id_anggota = '$id_anggota'
    ORDER BY a.tanggal_bayar DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Angsuran</title>
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
    <li class="nav-item"><a class="nav-link active" href="angsuran.php"><i class="bi bi-clock-history"></i> Riwayat Angsuran</a></li>
    <li class="nav-item"><a class="nav-link active" href="transaksi_barang.php"><i class="bi bi-cart"></i> Transaksi Barang</a></li>
    <hr>
    <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<main class="flex-fill bg-light">

<div class="p-3 bg-white shadow-sm">
  <h5 class="fw-semibold mb-0">Riwayat Angsuran</h5>
</div>

<div class="container-fluid p-4">

<div class="card shadow-sm">
<div class="card-body">

<h6 class="fw-semibold mb-3">Daftar Pembayaran Angsuran</h6>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
  <th>Tanggal Bayar</th>
  <th>Angsuran Ke</th>
  <th>Jumlah Bayar</th>
  <th>Keterangan</th>
</tr>
</thead>
<tbody>

<?php if (mysqli_num_rows($qAngsuran) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($qAngsuran)): ?>

<tr>
  <td><?= date('d/m/Y', strtotime($row['tanggal_bayar'])) ?></td>
  <td><?= $row['angsuran_ke'] ?></td>
  <td>Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
  <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="4" class="text-center text-muted">
    Belum ada data angsuran
  </td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>
</div>

</div>
</main>
</div>

</body>
</html>
