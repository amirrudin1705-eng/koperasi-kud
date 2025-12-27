<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "db_kud_simpan_pinjam";


$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
