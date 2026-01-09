<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

/* ===============================
 * FILTER TANGGAL (DEFAULT BULAN INI)
 * =============================== */
$awal  = $_GET['awal']  ?? '';
$akhir = $_GET['akhir'] ?? '';

$whereTanggal = '';

if (!empty($awal) && !empty($akhir)) {
    $whereTanggal = "WHERE tanggal BETWEEN '$awal' AND '$akhir'";
}


/* ===============================
 * TOTAL SIMPANAN (DANA MASUK)
 * =============================== */
$simpanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah),0) AS total 
    FROM simpanan 
    $whereTanggal
"));

/* ===============================
 * TOTAL ANGSURAN (DANA MASUK)
 * =============================== */
$angsuran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar),0) AS total 
    FROM angsuran 
    $whereTanggal
"));

/* ===============================
 * TOTAL PENJUALAN BARANG (DANA MASUK)
 * =============================== */
$penjualan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah * harga),0) AS total 
    FROM transaksi_barang
    $whereTanggal
"));

/* ===============================
 * TOTAL DANA MASUK KESELURUHAN
 * =============================== */
$totalDanaMasuk =
    ($simpanan['total'] ?? 0) +
    ($angsuran['total'] ?? 0) +
    ($penjualan['total'] ?? 0);
?>

<h4 class="fw-bold mb-2">Laporan Keuangan</h4>


<form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        <label>Tanggal Awal</label>
        <input type="date" name="awal"
               value="<?= htmlspecialchars($awal ?? '') ?>"
               class="form-control">
    </div>

    <div class="col-md-4">
        <label>Tanggal Akhir</label>
        <input type="date" name="akhir"
               value="<?= htmlspecialchars($akhir ?? '') ?>"
               class="form-control">
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
                    Rp <?= number_format($simpanan['total'],0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <small class="text-muted">Total Angsuran</small>
                <h5 class="fw-bold text-success">
                    Rp <?= number_format($angsuran['total'],0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <small class="text-muted">Penjualan Barang</small>
                <h5 class="fw-bold text-success">
                    Rp <?= number_format($penjualan['total'],0,',','.') ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm card-stat border-primary">
            <div class="card-body">
                <small class="text-muted">TOTAL DANA MASUK</small>
                <h4 class="fw-bold text-primary">
                    Rp <?= number_format($totalDanaMasuk,0,',','.') ?>
                </h4>
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
