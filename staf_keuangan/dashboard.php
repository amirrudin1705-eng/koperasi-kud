<?php
require '../auth/auth_staf.php';
require '../config/database.php';
require 'helpers/dashboard_data.php';

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-1">Dashboard</h4>
<p class="text-muted mb-4">Ringkasan data keuangan koperasi</p>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Simpanan</small>
                    <h5 class="fw-bold">Rp <?= number_format($totalSimpanan['total'],0,',','.') ?></h5>
                </div>
                <i class="bi bi-wallet2 fs-2 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Pinjaman Aktif</small>
                    <h5 class="fw-bold">Rp <?= number_format($totalPinjaman['total'],0,',','.') ?></h5>
                </div>
                <i class="bi bi-credit-card fs-2 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Angsuran</small>
                    <h5 class="fw-bold">Rp <?= number_format($totalAngsuran['total'],0,',','.') ?></h5>
                </div>
                <i class="bi bi-arrow-repeat fs-2 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Jumlah Anggota</small>
                    <h5 class="fw-bold"><?= $totalAnggota['total'] ?></h5>
                </div>
                <i class="bi bi-people fs-2 text-primary"></i>
            </div>
        </div>
    </div>

</div>
