<?php
require '../auth/auth_staf.php';
require '../config/database.php';

include 'layout/header.php';
include 'layout/sidebar.php';

/*
|--------------------------------------------------
| Ambil Data Anggota
|--------------------------------------------------
*/

// Auto-generate nomor anggota jika masih kosong
$anggotaKosong = mysqli_query($conn, "
    SELECT id_anggota 
    FROM anggota 
    WHERE nomor_anggota IS NULL
");

while ($a = mysqli_fetch_assoc($anggotaKosong)) {
    $id = $a['id_anggota'];
    $nomor = 'KUD-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);

    mysqli_query($conn, "
        UPDATE anggota 
        SET nomor_anggota = '$nomor'
        WHERE id_anggota = '$id'
    ");
}

$query = mysqli_query($conn, "
    SELECT 
        a.id_anggota,
        a.nomor_anggota,
        a.status_keanggotaan,
        u.nama,
        u.email
    FROM anggota a
    JOIN users u ON a.id_user = u.id_user
    ORDER BY u.nama ASC
");
?>

<h4 class="fw-bold mb-2">Data Anggota</h4>
<p class="text-muted mb-4">
    Daftar anggota koperasi yang terdaftar dalam sistem.
</p>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Nomor Anggota</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['nomor_anggota'] ?? '-' ?></td>
                            <td>
                                <?php if ($row['status_keanggotaan'] == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data anggota.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
