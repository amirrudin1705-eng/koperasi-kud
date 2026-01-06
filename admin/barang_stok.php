<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$id = (int)$_GET['id'];

$barang = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT nama_barang, stok FROM barang WHERE id_barang=$id")
);

if ($_POST) {
    $tambah = (int)$_POST['jumlah'];

    mysqli_query($conn, "
        UPDATE barang
        SET stok = stok + $tambah
        WHERE id_barang = $id
    ");

    header("Location: barang.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Tambah Stok</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<h4>Tambah Stok Barang</h4>

<div class="card p-3">
<p><strong>Barang:</strong> <?= $barang['nama_barang'] ?></p>
<p><strong>Stok Saat Ini:</strong> <?= $barang['stok'] ?></p>

<form method="post">
<div class="mb-3">
<label>Jumlah Tambah</label>
<input type="number" name="jumlah" class="form-control" required>
</div>

<button class="btn btn-success">Simpan</button>
<a href="barang.php" class="btn btn-secondary">Kembali</a>
</form>
</div>
</div>
</body>
</html>
