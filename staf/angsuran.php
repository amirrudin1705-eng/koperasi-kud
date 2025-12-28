<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

/*
|--------------------------------------------------
| Ambil Data Angsuran
|--------------------------------------------------
*/
$query = mysqli_query($conn, "
    SELECT 
        a.id_angsuran,
        a.angsuran_ke,
        a.tanggal_bayar,
        a.jumlah_bayar,
        a.keterangan,
        u.nama,
        p.jumlah_pinjaman
    FROM angsuran a
    JOIN pengajuan_pinjaman p ON a.id_pengajuan = p.id_pengajuan
    JOIN anggota ag ON p.id_anggota = ag.id_anggota
    JOIN users u ON ag.id_user = u.id_user
    ORDER BY a.tanggal_bayar DESC
");
?>

<h4 class="fw-bold mb-3">Data Angsuran</h4>
<p class="text-muted mb-4">
    Halaman ini digunakan untuk mencatat dan mencetak pembayaran angsuran pinjaman anggota.
</p>

<a href="angsuran_add.php" class="btn btn-primary mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Angsuran
</a>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Angsuran Ke</th>
                    <th>Tanggal Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Keterangan</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td>Rp <?= number_format($row['jumlah_pinjaman'],0,',','.') ?></td>
                            <td><?= $row['angsuran_ke'] ?></td>
                            <td><?= $row['tanggal_bayar'] ?></td>
                            <td>Rp <?= number_format($row['jumlah_bayar'],0,',','.') ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td>
                                <!-- CETAK KWITANSI -->
                                <a href="kwitansi_angsuran.php?id=<?= $row['id_angsuran'] ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-success mb-1">
                                   Cetak
                                </a>

                                <!-- HAPUS -->
                                <a href="angsuran_delete.php?id=<?= $row['id_angsuran'] ?>"
                                   onclick="return confirm('Yakin ingin menghapus data angsuran ini?')"
                                   class="btn btn-sm btn-danger mb-1">
                                   Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada data angsuran.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
