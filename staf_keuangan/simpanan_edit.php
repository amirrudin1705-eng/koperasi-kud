<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM simpanan WHERE id_simpanan='$id'"));

if ($_SERVER['REQUEST_METHOD']==='POST') {
    mysqli_query($conn, "
        UPDATE simpanan SET
        tanggal='$_POST[tanggal]',
        jenis_simpanan='$_POST[jenis_simpanan]',
        jumlah='$_POST[jumlah]',
        keterangan='$_POST[keterangan]'
        WHERE id_simpanan='$id'
    ");
    header("Location: simpanan.php");
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-3">Edit Simpanan</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label>Jenis Simpanan</label>
                <select name="jenis_simpanan" class="form-select">
                    <option value="pokok" <?= $data['jenis_simpanan']=='pokok'?'selected':'' ?>>Pokok</option>
                    <option value="wajib" <?= $data['jenis_simpanan']=='wajib'?'selected':'' ?>>Wajib</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <input type="text" name="keterangan" value="<?= $data['keterangan'] ?>" class="form-control">
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="simpanan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
