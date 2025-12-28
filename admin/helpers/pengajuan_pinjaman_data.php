<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   PROSES ACC / TOLAK
================================ */
if (isset($_POST['aksi'], $_POST['id_pengajuan'])) {

    $id_pengajuan = (int) $_POST['id_pengajuan'];
    $aksi = $_POST['aksi'];

if ($aksi === 'disetujui') {
    $statusBaru = 'berjalan';
} elseif ($aksi === 'ditolak') {
    $statusBaru = 'ditolak';
} else {
    $statusBaru = null;
}

if ($statusBaru) {
    mysqli_query($conn, "
        UPDATE pengajuan_pinjaman
        SET status = '$statusBaru'
        WHERE id_pengajuan = '$id_pengajuan'
    ");
}


    header("Location: pengajuan_pinjaman.php");
    exit;
}

/* ===============================
   AMBIL DATA PENGAJUAN
================================ */
$dataPengajuan = [];

$qData = mysqli_query($conn, "
    SELECT
        p.id_pengajuan,
        u.nama,
        p.jumlah_pinjaman,
        p.tenor,
        p.bunga,
        p.cicilan,
        p.status,
        p.tanggal_pengajuan
    FROM pengajuan_pinjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    ORDER BY p.tanggal_pengajuan DESC
");

if ($qData) {
    while ($row = mysqli_fetch_assoc($qData)) {
        $dataPengajuan[] = $row;
    }
}
