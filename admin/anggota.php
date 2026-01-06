<?php
session_start();
require_once '../config/database.php';

/* ===============================
   PROTEKSI AKSES ADMIN
================================ */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ===============================
   DATA & LOGIC ANGGOTA
================================ */
require_once 'helpers/anggota_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Anggota | Admin KUD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1f2937;
            position: fixed;
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
        .content {
            margin-left: 250px;
            padding: 24px;
        }
    </style>
</head>

<body>

<!-- ========== SIDEBAR ========== -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="anggota.php" class="active"><i class="bi bi-people me-2"></i> Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i> Simpanan</a>
    <a href="barang.php"><i class="bi bi-box-seam me-2"></i> Barang</a>
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
        <h4 class="fw-semibold mb-0">Manajemen Anggota</h4>
        <span class="text-muted">
            Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>
        </span>
    </div>

    <!-- CARD TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="fw-semibold mb-3">
                <i class="bi bi-people me-1"></i> Daftar Anggota Koperasi
            </h6>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (!empty($dataAnggota)): ?>
                        <?php foreach ($dataAnggota as $no => $row): ?>
                            <tr>
                                <td><?= $no + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <?php if ($row['status_keanggotaan'] === 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a
                                        href="anggota.php?id=<?= $row['id_anggota'] ?>&aksi=<?= $row['status_keanggotaan'] === 'aktif' ? 'nonaktif' : 'aktif' ?>"
                                        class="btn btn-sm <?= $row['status_keanggotaan'] === 'aktif' ? 'btn-danger' : 'btn-success' ?>"
                                        onclick="return confirm('Yakin ingin mengubah status keanggotaan?')"
                                    >
                                        <?= $row['status_keanggotaan'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Data anggota belum tersedia
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</main>

</body>
</html>
