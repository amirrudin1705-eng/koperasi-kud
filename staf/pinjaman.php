<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

$query = mysqli_query($conn, "
    SELECT 
        p.id_pengajuan,
        u.nama,
        p.tanggal_pengajuan,
        p.jumlah_pinjaman,
        p.tenor,
        p.bunga,
        p.cicilan,
        p.status
    FROM pengajuan_pinjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    ORDER BY p.tanggal_pengajuan DESC
");
?>

<h4 class="fw-bold mb-3">Data Pinjaman</h4>
<p class="text-muted mb-4">
    Halaman ini hanya untuk monitoring pinjaman anggota.
</p>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Tenor</th>
                    <th>Cicilan / Bulan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row=mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['tanggal_pengajuan'] ?></td>
                    <td>Rp <?= number_format($row['jumlah_pinjaman'],0,',','.') ?></td>
                    <td><?= $row['tenor'] ?> bulan</td>
                    <td>Rp <?= number_format($row['cicilan'],0,',','.') ?></td>
<td>
    <?php if ($row['status'] == 'menunggu'): ?>
        <span class="badge bg-warning text-dark">Menunggu</span>

    <?php elseif ($row['status'] == 'berjalan'): ?>
        <span class="badge bg-success">Disetujui</span>

    <?php elseif ($row['status'] == 'lunas'): ?>
        <span class="badge bg-primary">Lunas</span>

<<<<<<< HEAD
    <?php elseif ($row['status'] == 'ditolak'): ?>
        <span class="badge bg-danger">Ditolak</span>

    <?php else: ?>
        <span class="badge bg-secondary">Tidak Diketahui</span>
=======
    <?php else: ?>
        <span class="badge bg-danger">Ditolak</span>
>>>>>>> e67b832d1f9bd2ef40512f1aa1c2dd6dc22db51f
    <?php endif; ?>
</td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
