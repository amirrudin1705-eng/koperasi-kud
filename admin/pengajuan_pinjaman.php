<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ===================================================
   PROSES ACC / TOLAK
   =================================================== */
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
              AND status = 'menunggu'
        ");
    }

    header("Location: pengajuan_pinjaman.php");
    exit;
}

/* ===================================================
   AMBIL DATA PENGAJUAN
   =================================================== */
$dataPengajuan = [];

$qData = mysqli_query($conn, "
    SELECT
        p.id_pengajuan,
        u.nama,
        p.jumlah_pinjaman,
        p.tenor,
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Pinjaman | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background:#f5f6fa;
}
.sidebar {
    width:250px;
    background:#1f2937;
    min-height:100vh;
    position:fixed;
}
.sidebar a {
    display:block;
    padding:12px 20px;
    color:#cbd5e1;
    text-decoration:none;
}
.sidebar a:hover,
.sidebar a.active {
    background:#374151;
    color:#fff;
}
.content {
    margin-left:250px;
    padding:24px;
}

/* BADGE STATUS */
.badge-menunggu { background:#facc15; color:#000; }
.badge-berjalan { background:#3b82f6; color:#fff; }
.badge-lunas { background:#16a34a; color:#fff; }
.badge-ditolak { background:#ef4444; color:#fff; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h5 class="text-white text-center py-3 mb-0">KUD Admin</h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="anggota.php"><i class="bi bi-people me-2"></i> Anggota</a>
    <a href="simpanan.php"><i class="bi bi-wallet2 me-2"></i> Simpanan</a>
    <a href="pengajuan_pinjaman.php" class="active">
        <i class="bi bi-cash-coin me-2"></i> Pengajuan Pinjaman
    </a>
    <a href="pinjaman.php"><i class="bi bi-cash-coin me-2"></i> Pinjaman</a>
    <a href="angsuran.php"><i class="bi bi-arrow-repeat me-2"></i> Angsuran</a>
    <a href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
    <a href="pengaturan.php"><i class="bi bi-gear me-2"></i> Pengaturan</a>
    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</aside>

<!-- CONTENT -->
<main class="content">

<h4 class="mb-4">Data Pengajuan Pinjaman</h4>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Jumlah</th>
    <th>Tenor</th>
    <th>Cicilan / Bulan</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php if (!empty($dataPengajuan)): ?>
<?php $no=1; foreach ($dataPengajuan as $row): ?>

<?php
$status = $row['status'];
$label = '';
$badgeClass = '';

if ($status === 'menunggu') {
    $label = 'Menunggu';
    $badgeClass = 'badge-menunggu';
} elseif ($status === 'berjalan') {
    $label = 'Berjalan';
    $badgeClass = 'badge-berjalan';
} elseif ($status === 'lunas') {
    $label = 'Lunas';
    $badgeClass = 'badge-lunas';
} elseif ($status === 'ditolak') {
    $label = 'Ditolak';
    $badgeClass = 'badge-ditolak';
}
?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['nama']); ?></td>
    <td>Rp <?= number_format($row['jumlah_pinjaman'],0,',','.'); ?></td>
    <td><?= $row['tenor']; ?> bln</td>
    <td>Rp <?= number_format($row['cicilan'],0,',','.'); ?></td>
    <td>
        <span class="badge <?= $badgeClass; ?>">
            <?= $label; ?>
        </span>
    </td>
    <td class="text-center">

    <?php if ($status === 'menunggu'): ?>

        <form method="post" class="d-inline">
            <input type="hidden" name="id_pengajuan" value="<?= $row['id_pengajuan']; ?>">
            <button name="aksi" value="disetujui" class="btn btn-success btn-sm">
                ACC
            </button>
        </form>

        <form method="post" class="d-inline">
            <input type="hidden" name="id_pengajuan" value="<?= $row['id_pengajuan']; ?>">
            <button name="aksi" value="ditolak" class="btn btn-danger btn-sm">
                Tolak
            </button>
        </form>

    <?php elseif ($status === 'berjalan'): ?>
        <span class="text-muted">Pinjaman Aktif</span>

    <?php elseif ($status === 'lunas'): ?>
        <span class="text-success">Lunas</span>

    <?php elseif ($status === 'ditolak'): ?>
        <span class="text-danger">Ditolak</span>

    <?php endif; ?>

    </td>
</tr>

<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="7" class="text-center text-muted">
        Belum ada pengajuan pinjaman
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>

</main>
</body>
</html>
