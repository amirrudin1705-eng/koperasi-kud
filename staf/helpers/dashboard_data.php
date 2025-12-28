<?php
require '../auth/auth_staf.php';
require '../config/database.php';
?>

<?php

$totalSimpanan = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COALESCE(SUM(jumlah), 0) AS total 
        FROM simpanan
    ")
);

$totalPinjaman = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COALESCE(SUM(jumlah_pinjaman), 0) AS total 
        FROM pengajuan_pinjaman
        WHERE status = 'berjalan'
    ")
);

$totalAngsuran = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COALESCE(SUM(jumlah_bayar), 0) AS total 
        FROM angsuran
    ")
);

$totalAnggota = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total 
        FROM anggota
        WHERE status_keanggotaan = 'aktif'
    ")
);
