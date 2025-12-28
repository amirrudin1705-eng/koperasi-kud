<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   DEFAULT AMAN
================================ */
$dataAngsuran = [];

/* ===============================
   AMBIL DATA ANGSURAN
================================ */
$qData = mysqli_query($conn, "
    SELECT
        a.id_angsuran,
        u.nama,
        p.jumlah_pinjaman,
        p.tenor,
        a.angsuran_ke,
        a.jumlah_bayar,
        a.tanggal_bayar
    FROM angsuran a
    JOIN pengajuan_pinjaman p ON a.id_pengajuan = p.id_pengajuan
    JOIN anggota ag ON p.id_anggota = ag.id_anggota
    JOIN users u ON ag.id_user = u.id_user
    ORDER BY a.tanggal_bayar DESC
");

if ($qData) {
    while ($row = mysqli_fetch_assoc($qData)) {
        $dataAngsuran[] = $row;
    }
}
