<?php
require '../auth/auth_staf.php';
require '../config/database.php';
?>

<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

$query = mysqli_query($conn, "
    SELECT s.*, a.nomor_anggota, u.nama
    FROM simpanan s
    JOIN anggota a ON s.id_anggota = a.id_anggota
    JOIN users u ON a.id_user = u.id_user
    ORDER BY s.tanggal DESC
");
?>

<h4 class="fw-bold mb-3">Data Simpanan</h4>

<a href="simpanan_add.php" class="btn btn-primary mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Simpanan
</a>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Anggota</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row=mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td class="text-capitalize"><?= $row['jenis_simpanan'] ?></td>
                    <td>Rp <?= number_format($row['jumlah'],0,',','.') ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td>
                        <a href="simpanan_edit.php?id=<?= $row['id_simpanan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="simpanan_delete.php?id=<?= $row['id_simpanan'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus data ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
