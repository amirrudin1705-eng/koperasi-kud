<?php
/******************************************
 * DATA & AKSI ANGGOTA - ADMIN
 ******************************************/

if (!isset($conn)) {
    require_once __DIR__ . '/../../config/database.php';
}

/* ==============================
   UPDATE STATUS KEANGGOTAAN
   (JIKA ADA AKSI DARI ADMIN)
================================ */
if (isset($_GET['aksi'], $_GET['id'])) {
    $id_anggota = (int) $_GET['id'];
    $aksi       = $_GET['aksi'];

    if (in_array($aksi, ['aktif', 'nonaktif'])) {
        mysqli_query(
            $conn,
            "UPDATE anggota
             SET status_keanggotaan = '$aksi'
             WHERE id_anggota = '$id_anggota'"
        );
    }

    // Redirect supaya tidak double action saat refresh
    header("Location: anggota.php");
    exit;
}

/* ==============================
   AMBIL DATA ANGGOTA
================================ */
$qAnggota = mysqli_query($conn, "
    SELECT 
        a.id_anggota,
        u.id_user,
        u.nama,
        u.email,
        a.status_keanggotaan
    FROM anggota a
    JOIN users u ON a.id_user = u.id_user
    ORDER BY u.nama ASC
");

$dataAnggota = [];

if ($qAnggota) {
    while ($row = mysqli_fetch_assoc($qAnggota)) {
        $dataAnggota[] = $row;
    }
}
