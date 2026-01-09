<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

/* QUERY DATA BARANG */
$data = mysqli_query($conn, "
    SELECT 
        id_barang,
        nama_barang,
        stok,
        satuan,
        harga_jual,
        is_active
    FROM barang
    WHERE is_active = 1
    ORDER BY nama_barang ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Barang | Admin KUD</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background: #f5f6fa; }
.admin-wrapper { display: flex; min-height: 100vh; }
.sidebar { width: 250px; background: #1f2937; }
.sidebar a { display: block; padding: 12px 20px; color: #cbd5e1; text-decoration: none; }
.sidebar a:hover, .sidebar a.active { background: #374151; color: #fff; }
.content { flex: 1; padding: 24px; }
.card { border-radius: 12px; }
</style>
</head>

<body>
<div class="admin-wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i>Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i>Simpanan</a>
    <a href="barang.php" class="active"><i class="bi bi-box-seam me-2"></i>Barang</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i>Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i>Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right me-2"></i>Logout
    </a>
</aside>

<!-- CONTENT -->
<main class="content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Data Barang</h4>
    <a href="barang_tambah.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Barang
    </a>
</div>

<div class="card shadow-sm">
<div class="card-body">
<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th width="5%">No</th>
    <th>Nama Barang</th>
    <th>Stok</th>
    <th>Harga Jual</th>
    <th width="20%">Aksi</th>
</tr>
</thead>
<tbody>

<?php if ($data && mysqli_num_rows($data) > 0): ?>
<?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
    <td><?= $row['stok'].' '.$row['satuan'] ?></td>
    <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
    <td>
        <a href="barang_stok.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-success">
            + Stok
        </a>
        <a href="barang_edit.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-warning">
            Edit
        </a>
        <a href="barang_hapus.php?id=<?= $row['id_barang'] ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus barang ini?')">
            Hapus
        </a>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="5" class="text-center text-muted">
        Belum ada data barang
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>
</div>

</main>
</div>
</body>
</html>
