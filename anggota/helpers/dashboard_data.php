<?php
if (!isset($conn) || !isset($id_user)) {
    die('Akses tidak valid');
}

/* ==================================================
   1. AMBIL ID ANGGOTA
================================================== */
$qAnggota = mysqli_query($conn, "
    SELECT id_anggota
    FROM anggota
    WHERE id_user = '$id_user'
");
$dataAnggota = mysqli_fetch_assoc($qAnggota);
$id_anggota = $dataAnggota['id_anggota'] ?? 0;

/* ==================================================
   DEFAULT VALUE (ANTI ERROR)
================================================== */
$pinjamanAktif      = 0;
$sisaAngsuran       = 0;
$statusKeanggotaan  = 'Aktif';

$jatuhTempo         = null;
$angsuranKe         = 0;
$nominalAngsuran    = 0;

/* ==================================================
   JIKA ANGGOTA ADA
================================================== */
if ($id_anggota) {

    /* ==============================================
       2. AMBIL PINJAMAN AKTIF (DISETUJUI)
       - Fokus 1 pinjaman aktif terakhir
    =============================================== */
    $qPinjaman = mysqli_query($conn, "
        SELECT 
            p.id_pengajuan,
            p.jumlah_pinjaman,
            p.cicilan,
            p.tenor,
            p.tanggal_pengajuan
        FROM pengajuan_pinjaman p
        WHERE p.id_anggota = '$id_anggota'
          AND p.status = 'disetujui'
        ORDER BY p.tanggal_pengajuan DESC
        LIMIT 1
    ");

    $pinjaman = mysqli_fetch_assoc($qPinjaman);

    if ($pinjaman) {

        $idPengajuan      = $pinjaman['id_pengajuan'];
        $pinjamanAktif    = $pinjaman['jumlah_pinjaman'];
        $nominalAngsuran  = $pinjaman['cicilan'];
        $tanggalPengajuan = $pinjaman['tanggal_pengajuan'];

        /* ==========================================
           3. HITUNG TOTAL ANGSURAN YANG SUDAH DIBAYAR
        =========================================== */
        $qAngsuran = mysqli_query($conn, "
            SELECT 
                COUNT(*) AS total_bayar,
                COALESCE(SUM(jumlah_bayar), 0) AS total_nominal,
                MAX(angsuran_ke) AS terakhir_ke,
                MAX(tanggal_bayar) AS terakhir_bayar
            FROM angsuran
            WHERE id_pengajuan = '$idPengajuan'
        ");

        $angsuran = mysqli_fetch_assoc($qAngsuran);

        $totalDibayar   = $angsuran['total_nominal'] ?? 0;
        $angsuranTerakhir = $angsuran['terakhir_ke'] ?? 0;
        $tanggalBayarTerakhir = $angsuran['terakhir_bayar'] ?? null;

        /* ==========================================
           4. HITUNG SISA ANGSURAN
        =========================================== */
        $sisaAngsuran = max($pinjamanAktif - $totalDibayar, 0);

        /* ==========================================
           5. HITUNG ANGSURAN KE & JATUH TEMPO (BENAR)
           ATURAN:
           - JIKA SUDAH PERNAH BAYAR:
             jatuh tempo = tanggal bayar terakhir + 1 bulan
           - JIKA BELUM PERNAH BAYAR:
             jatuh tempo = tanggal pengajuan + 1 bulan
        =========================================== */
        if ($tanggalBayarTerakhir) {
            // Sudah pernah bayar
            $angsuranKe = $angsuranTerakhir + 1;
            $jatuhTempo = date(
                'Y-m-d',
                strtotime('+1 month', strtotime($tanggalBayarTerakhir))
            );
        } else {
            // Belum pernah bayar
            $angsuranKe = 1;
            $jatuhTempo = date(
                'Y-m-d',
                strtotime('+1 month', strtotime($tanggalPengajuan))
            );
        }
    }
}
