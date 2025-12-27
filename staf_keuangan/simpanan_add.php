<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$anggota = mysqli_query($conn, "
    SELECT a.id_anggota, u.nama 
    FROM anggota a 
    JOIN users u ON a.id_user=u.id_user
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $tanggal    = $_POST['tanggal'];
    $jenis      = $_POST['jenis_simpanan'];
    $jumlah     = $_POST['jumlah'];
    $ket        = $_POST['keterangan'];

    mysqli_query($conn, "
        INSERT INTO simpanan 
        (id_anggota, tanggal, jenis_simpanan, jumlah, keterangan)
        VALUES ('$id_anggota','$tanggal','$jenis','$jumlah','$ket')
    ");

    header("Location: simpanan.php");
    exit;
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-3">Tambah Simpanan</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label>Anggota</label>
                <select name="id_anggota" class="form-select" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php while($a=mysqli_fetch_assoc($anggota)): ?>
                        <option value="<?= $a['id_anggota'] ?>"><?= $a['nama'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Jenis Simpanan</label>
                <select name="jenis_simpanan" class="form-select" required>
                    <option value="pokok">Pokok</option>
                    <option value="wajib">Wajib</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="simpanan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
