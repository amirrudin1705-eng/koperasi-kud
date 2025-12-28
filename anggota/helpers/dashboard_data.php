<?php
/* ==================================================
   VALIDASI DASAR
================================================== */
if (!isset($conn) || !isset($id_user)) {
    die('Akses tidak valid');
}

/* ==================================================
   DEFAULT AMAN (WAJIB UNTUK USER BARU)
================================================== */
$statusKeanggotaan = 'Tidak Aktif';

$pinjamanAktif     = 0;
$sisaAngsuran      = 0;
$nominalAngsuran   = 0;
$angsuranKe        = 0;
$jatuhTempo        = '-';

/* ==================================================
   1. AMBIL DATA ANGGOTA
================================================== */
$qAnggota = mysqli_query($conn, "
    SELECT id_anggota, status_keanggotaan
    FROM anggota
    WHERE id_user = '$id_user'
    LIMIT 1
");

$dataAnggota = mysqli_fetch_assoc($qAnggota);

$id_anggota = $dataAnggota['id_anggota'] ?? 0;

if (
    isset($dataAnggota['status_keanggotaan']) &&
    strtolower($dataAnggota['status_keanggotaan']) === 'aktif'
) {
    $statusKeanggotaan = 'Aktif';
}

/* ==================================================
   2. AMBIL PINJAMAN AKTIF (STATUS DISETUJUI)
================================================== */
if ($id_anggota) {

    $qPinjaman = mysqli_query($conn, "
        SELECT 
            id_pengajuan,
            jumlah_pinjaman,
            cicilan,
            tenor,
            tanggal_pengajuan
        FROM pengajuan_pinjaman
        WHERE id_anggota = '$id_anggota'
          AND status = 'berjalan'
        ORDER BY tanggal_pengajuan DESC
        LIMIT 1
    ");

    $pinjaman = mysqli_fetch_assoc($qPinjaman);

    if ($pinjaman) {

        $idPengajuan      = $pinjaman['id_pengajuan'];
        $pinjamanAktif   = (float) $pinjaman['jumlah_pinjaman'];
        $nominalAngsuran = (float) $pinjaman['cicilan'];
        $tanggalPengajuan = $pinjaman['tanggal_pengajuan'];

        /* ==========================================
           3. HITUNG ANGSURAN YANG SUDAH DIBAYAR
        =========================================== */
        $qAngsuran = mysqli_query($conn, "
            SELECT 
                COALESCE(SUM(jumlah_bayar), 0) AS total_dibayar,
                MAX(angsuran_ke) AS terakhir_ke,
                MAX(tanggal_bayar) AS terakhir_bayar
            FROM angsuran
            WHERE id_pengajuan = '$idPengajuan'
        ");

        $angsuran = mysqli_fetch_assoc($qAngsuran);

        $totalDibayar        = (float) ($angsuran['total_dibayar'] ?? 0);
        $angsuranTerakhir   = (int) ($angsuran['terakhir_ke'] ?? 0);
        $tanggalBayarTerakhir = $angsuran['terakhir_bayar'] ?? null;

        /* ==========================================
           4. HITUNG SISA ANGSURAN
        =========================================== */
        $sisaAngsuran = max($pinjamanAktif - $totalDibayar, 0);

        /* ==========================================
           5. HITUNG ANGSURAN KE & JATUH TEMPO
        =========================================== */
        if ($tanggalBayarTerakhir) {
            // Sudah pernah bayar
            $angsuranKe = $angsuranTerakhir + 1;
            $jatuhTempo = date(
                'Y-m-d',
                strtotime('+1 month', strtotime($tanggalBayarTerakhir))
            );
        } else {
            // Belum pernah bayar sama sekali
            $angsuranKe = 1;
            $jatuhTempo = date(
                'Y-m-d',
                strtotime('+1 month', strtotime($tanggalPengajuan))
            );
        }
    }
}
