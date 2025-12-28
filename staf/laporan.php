<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

// filter tanggal
$awal  = $_GET['awal'] ?? date('Y-m-01');
$akhir = $_GET['akhir'] ?? date('Y-m-t');

// total simpanan
$simpanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah) total 
    FROM simpanan 
    WHERE tanggal BETWEEN '$awal' AND '$akhir'
"));

// total angsuran
$angsuran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) total 
    FROM angsuran 
    WHERE tanggal_bayar BETWEEN '$awal' AND '$akhir'
"));
?>

<h4 class="fw-bold mb-2">Laporan Keuangan</h4>
<p class="text-muted mb-4">Periode <?= $awal ?> s/d <?= $akhir ?></p>

<form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        <label>Tanggal Awal</label>
        <input type="date" name="awal" value="<?= $awal ?>" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Tanggal Akhir</label>
        <input type="date" name="akhir" value="<?= $akhir ?>" class="form-control">
    </div>
    <div class="col-md-4 align-self-end">
        <button class="btn btn-primary w-100">
            <i class="bi bi-filter"></i> Tampilkan
        </button>
    </div>
</form>

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <small class="text-muted">Total Simpanan</small>
                <h5 class="fw-bold text-success">
                    Rp <?= number_format($simpanan['total'] ?? 0,0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <small class="text-muted">Total Angsuran</small>
                <h5 class="fw-bold text-success">
                    Rp <?= number_format($angsuran['total'] ?? 0,0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <small class="text-muted">Total Dana Masuk</small>
                <h5 class="fw-bold text-primary">
                    Rp <?= number_format(($simpanan['total'] ?? 0)+($angsuran['total'] ?? 0),0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

</div>

<a href="laporan_print.php?awal=<?= $awal ?>&akhir=<?= $akhir ?>" 
   target="_blank" 
   class="btn btn-outline-primary">
   <i class="bi bi-printer"></i> Cetak Laporan
</a>

<?php include 'layout/footer.php'; ?>
