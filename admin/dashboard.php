<?php
/*************************************************
 * DASHBOARD ADMIN - KUD SIMPAN PINJAM
 *************************************************/
session_start();

/* ==== PROTEKSI LOGIN ==== */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require_once '../config/database.php';
require_once 'helpers/dashboard_data.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | KUD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- STYLE DASHBOARD -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1f2937;
            position: fixed;
        }
        .sidebar h5 {
            border-bottom: 1px solid #374151;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #374151;
            color: #ffffff;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 24px;
        }

        /* Card */
        .card-stat {
            border: none;
            border-radius: 12px;
        }
        .card-stat i {
            font-size: 28px;
        }
    </style>
</head>

<body>

<!-- ========== SIDEBAR ========== -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i> Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i> Simpanan</a>
    <a href="pengajuan_pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pengajuan Pinjaman</a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i> Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i> Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</aside>

<!-- ========== CONTENT ========== -->
<main class="content">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Dashboard</h4>
        <span class="text-muted">
            Halo, <?= $_SESSION['nama'] ?? 'Admin'; ?>
        </span>
    </div>

    <!-- STATISTIK -->
    <div class="row g-4 mb-4">

        <!-- Total Anggota -->
        <div class="col-md-3">
            <div class="card card-stat p-3 shadow-sm">
                <small class="text-muted">Total Anggota</small>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0"><?= $totalAnggota; ?></h4>
                    <i class="bi bi-people text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Total Simpanan -->
        <div class="col-md-3">
            <div class="card card-stat p-3 shadow-sm">
                <small class="text-muted">Total Simpanan</small>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">
                        Rp <?= number_format($totalSimpanan, 0, ',', '.'); ?>
                    </h4>
                    <i class="bi bi-wallet2 text-success"></i>
                </div>
            </div>
        </div>

        <!-- Pinjaman Aktif -->
        <div class="col-md-3">
            <div class="card card-stat p-3 shadow-sm">
                <small class="text-muted">Pinjaman Aktif</small>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">
                        Rp <?= number_format($totalPinjaman, 0, ',', '.'); ?>
                    </h4>
                    <i class="bi bi-cash-coin text-warning"></i>
                </div>
            </div>
        </div>

        <!-- Tunggakan -->
        <div class="col-md-3">
            <div class="card card-stat p-3 shadow-sm">
                <small class="text-muted">Tunggakan</small>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0 text-danger">
                        <?= $totalTunggakan; ?>
                    </h4>
                    <i class="bi bi-exclamation-circle text-danger"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- INFO -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold">Informasi</h6>
            <p class="text-muted mb-0">
                Ringkasan kondisi koperasi untuk membantu kepala koperasi
                dalam mengambil keputusan secara cepat dan tepat.
            </p>
        </div>
    </div>

</main>

</body>
</html>
