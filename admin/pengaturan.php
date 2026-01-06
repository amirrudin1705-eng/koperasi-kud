<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once 'helpers/pengaturan_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengaturan | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body { font-family:'Poppins',sans-serif; background:#f5f6fa; }
.sidebar { width:250px; background:#1f2937; min-height:100vh; position:fixed; }
.sidebar a { display:block; padding:12px 20px; color:#cbd5e1; text-decoration:none; }
.sidebar a:hover, .sidebar a.active { background:#374151; color:#fff; }
.content { margin-left:250px; padding:24px; }
</style>
</head>

<body>

<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i> Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i> Simpanan</a>
    <a href="barang.php"><i class="bi bi-box-seam me-2"></i> Barang</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i> Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
    <a href="pengaturan.php" class="active"><i class="bi bi-gear me-2"></i> Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</aside>

<main class="content">

<h4 class="mb-4">Pengaturan Sistem</h4>

<!-- AKUN ADMIN -->
<div class="card mb-4">
<div class="card-body">
<h6>Akun Admin</h6>
<form method="post">
    <input type="hidden" name="update_admin">
    <div class="mb-2">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= $dataAdmin['nama'] ?>" required>
    </div>
    <div class="mb-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $dataAdmin['email'] ?>" required>
    </div>
    <button class="btn btn-primary mt-2">Simpan</button>
</form>
</div>
</div>

<!-- KOPERASI -->
<div class="card mb-4">
<div class="card-body">
<h6>Pengaturan Koperasi</h6>
<form method="post">
    <input type="hidden" name="update_koperasi">
    <input type="text" name="nama_koperasi" class="form-control mb-2" placeholder="Nama Koperasi" value="<?= $setting['nama_koperasi'] ?? '' ?>">
    <textarea name="alamat" class="form-control mb-2" placeholder="Alamat"><?= $setting['alamat'] ?? '' ?></textarea>
    <input type="text" name="telepon" class="form-control mb-2" placeholder="Telepon" value="<?= $setting['telepon'] ?? '' ?>">
    <input type="email" name="email_koperasi" class="form-control mb-2" placeholder="Email" value="<?= $setting['email'] ?? '' ?>">
    <button class="btn btn-primary">Simpan</button>
</form>
</div>
</div>

<!-- PINJAMAN -->
<div class="card">
<div class="card-body">
<h6>Pengaturan Pinjaman</h6>
<form method="post">
    <input type="hidden" name="update_pinjaman">
    <div class="mb-2">
        <label>Bunga Default (%)</label>
        <input type="number" step="0.01" name="bunga_default" class="form-control" value="<?= $setting['bunga_default'] ?? 0 ?>">
    </div>
    <div class="mb-2">
        <label>Tenor Maksimal (bulan)</label>
        <input type="number" name="tenor_maks" class="form-control" value="<?= $setting['tenor_maks'] ?? 12 ?>">
    </div>
    <button class="btn btn-primary">Simpan</button>
</form>
</div>
</div>

</main>
</body>
</html>
