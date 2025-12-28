<?php
require '../auth/auth_staf.php';
require '../config/database.php';
?>

<div class="d-flex">
    <aside class="sidebar bg-primary text-white p-3" style="width: 250px;">
        <h5 class="mb-4 fw-bold">
            <i class="bi bi-cash-coin"></i> Staff Keuangan
        </h5>

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link text-white active" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="anggota.php">
                    <i class="bi bi-people me-2"></i> Data Anggota
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="simpanan.php">
                    <i class="bi bi-wallet2 me-2"></i> Simpanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="pinjaman.php">
                    <i class="bi bi-credit-card me-2"></i> Pinjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="angsuran.php">
                    <i class="bi bi-arrow-repeat me-2"></i> Angsuran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="laporan.php">
                    <i class="bi bi-file-earmark-text me-2"></i> Laporan
                </a>
            </li>

            <hr class="text-white">

            <li class="nav-item">
                <a class="nav-link text-warning" href="../auth/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <main class="flex-grow-1 p-4">

