<?php
require '../auth/auth_staf.php';
require '../config/database.php';
?>

<?php
$totalSimpanan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(jumlah) total FROM simpanan")
);

$totalPinjaman = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT SUM(jumlah_pinjaman) total 
        FROM pengajuan_pinjaman 
        WHERE status='disetujui'
    ")
);

$totalAngsuran = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(jumlah_bayar) total FROM angsuran")
);

$totalAnggota = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM anggota")
);
