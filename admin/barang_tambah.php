<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/database.php';

/* ===============================
   PROSES SIMPAN
================================ */
if (isset($_POST['simpan_barang'])) {

    $nama   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok   = (float) $_POST['stok'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $beli   = (float) $_POST['harga_beli'];
    $jual   = (float) $_POST['harga_jual'];

    mysqli_query($conn, "
        INSERT INTO barang (nama_barang, stok, satuan, harga_beli, harga_jual)
        VALUES ('$nama', $stok, '$satuan', $beli, $jual)
    ");

    // ⛔ JANGAN KE DASHBOARD
    header("Location: barang.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Barang | Admin KUD</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body { font-family:'Poppins',sans-serif; background:#f5f6fa; }
.sidebar {
    width:250px; min-height:100vh;
    background:#1f2937; position:fixed;
}
.sidebar a {
    display:block; padding:12px 20px;
    color:#cbd5e1; text-decoration:none;
}
.sidebar a:hover, .sidebar a.active {
    background:#374151; color:#fff;
}
.content { margin-left:250px; padding:24px; }
.card { border-radius:14px; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="barang.php" class="active"><i class="bi bi-box-seam me-2"></i>Barang</a>
    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right me-2"></i>Logout
    </a>
</aside>

<!-- CONTENT -->
<main class="content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Tambah Barang</h4>
    <a href="barang.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
<div class="card-body p-4">

<!-- 🔑 ACTION DIKOSONGKAN = SUBMIT KE FILE INI -->
<form method="post" action="">

<div class="mb-3">
    <label class="form-label">Nama Barang</label>
    <input type="text" name="nama_barang" class="form-control" required>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Stok Awal</label>
        <input type="number" step="0.01" name="stok" class="form-control" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Satuan</label>
        <select name="satuan" class="form-select" required>
            <option value="">-- pilih --</option>
            <option value="kg">Kg</option>
            <option value="pcs">Pcs</option>
            <option value="liter">Liter</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Harga Beli</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="0.01" name="harga_beli" class="form-control" required>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label">Harga Jual</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="0.01" name="harga_jual" class="form-control" required>
        </div>
    </div>
</div>

<!-- 🔑 NAME PADA BUTTON -->
<button type="submit" name="simpan_barang" class="btn btn-success px-4">
    <i class="bi bi-save"></i> Simpan Barang
</button>

</form>

</div>
</div>

</main>
</body>
</html>
