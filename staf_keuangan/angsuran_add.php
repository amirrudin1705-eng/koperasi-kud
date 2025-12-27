<?php
require '../auth/auth_staf.php';
require '../config/database.php';

/* =========================
   AMBIL DATA PINJAMAN
   ========================= */
$pinjaman = mysqli_query($conn, "
    SELECT 
        p.id_pengajuan,
        u.nama,
        p.tenor,
        p.cicilan,
        IFNULL(SUM(a.jumlah_bayar),0) AS total_bayar,
        (p.tenor * p.cicilan) - IFNULL(SUM(a.jumlah_bayar),0) AS sisa
    FROM pengajuan_pinjaman p
    JOIN anggota ag ON p.id_anggota = ag.id_anggota
    JOIN users u ON ag.id_user = u.id_user
    LEFT JOIN angsuran a ON p.id_pengajuan = a.id_pengajuan
    WHERE p.status = 'disetujui'
    GROUP BY p.id_pengajuan
    HAVING sisa > 0
");

/* =========================
   PROSES SIMPAN
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pengajuan = $_POST['id_pengajuan'];
    $tanggal      = $_POST['tanggal_bayar'];
    $jumlah       = $_POST['jumlah_bayar'];
    $keterangan   = $_POST['keterangan'];

    // hitung angsuran ke
    $qKe = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT COUNT(*) total 
        FROM angsuran 
        WHERE id_pengajuan='$id_pengajuan'
    "));
    $angsuran_ke = $qKe['total'] + 1;

    // ambil data pinjaman
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT 
            p.tenor,
            p.cicilan,
            IFNULL(SUM(a.jumlah_bayar),0) AS total_bayar
        FROM pengajuan_pinjaman p
        LEFT JOIN angsuran a ON p.id_pengajuan = a.id_pengajuan
        WHERE p.id_pengajuan='$id_pengajuan'
    "));

    $total_cicilan = $cek['tenor'] * $cek['cicilan'];
    $sisa = $total_cicilan - $cek['total_bayar'];

    // VALIDASI
    if ($jumlah > $sisa) {
        echo "<script>
            alert('Jumlah pembayaran melebihi sisa pinjaman!');
            history.back();
        </script>";
        exit;
    }

    // insert angsuran
    mysqli_query($conn, "
        INSERT INTO angsuran
        (id_pengajuan, tanggal_bayar, jumlah_bayar, angsuran_ke, keterangan)
        VALUES
        ('$id_pengajuan','$tanggal','$jumlah','$angsuran_ke','$keterangan')
    ");

    // update status lunas
    $cekLunas = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT IFNULL(SUM(jumlah_bayar),0) total
        FROM angsuran
        WHERE id_pengajuan='$id_pengajuan'
    "));

// Jika total angsuran sudah memenuhi total cicilan,
// maka pinjaman dianggap lunas (tidak mengubah status pengajuan)

    header("Location: angsuran.php");
    exit;
}
?>

<?php
include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-3">Tambah Angsuran</h4>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="post">

            <div class="mb-3">
                <label>Pinjaman</label>
                <select name="id_pengajuan" class="form-select" required>
                    <option value="">-- Pilih Pinjaman --</option>
                    <?php while($p = mysqli_fetch_assoc($pinjaman)): ?>
                        <option value="<?= $p['id_pengajuan'] ?>">
                            <?= $p['nama'] ?> | Sisa: Rp <?= number_format($p['sisa'],0,',','.') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Angsuran Ke</label>
                <input type="text" class="form-control" value="Otomatis oleh sistem" readonly>
            </div>

            <div class="mb-3">
                <label>Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jumlah Bayar</label>
                <input type="number" name="jumlah_bayar" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="angsuran.php" class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
