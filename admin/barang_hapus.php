<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: barang.php");
    exit;
}

mysqli_query($conn, "
    UPDATE barang 
    SET is_active = 0 
    WHERE id_barang = $id
");

header("Location: barang.php");
exit;
