<?php
require '../auth/auth_staf.php';
require '../config/database.php';

/* BARANG (AMBIL HARGA + SATUAN) */
$barang = mysqli_query($conn, "
    SELECT id_barang, nama_barang, stok, satuan, harga_jual
    FROM barang
    WHERE stok > 0
    ORDER BY nama_barang ASC
");

/* ANGGOTA */
$anggota = mysqli_query($conn, "
    SELECT a.id_anggota, u.nama
    FROM anggota a
    JOIN users u ON a.id_user = u.id_user
    WHERE a.status_keanggotaan = 'aktif'
    ORDER BY u.nama ASC
");

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<h4 class="fw-bold mb-1">Transaksi Penjualan Barang</h4>
<p class="text-muted mb-4">Input transaksi penjualan barang koperasi</p>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
    <?php
    if ($_GET['error']=='stok') echo 'Stok barang tidak mencukupi';
    elseif ($_GET['error']=='saldo') echo 'Saldo simpanan tidak mencukupi';
    else echo 'Terjadi kesalahan';
    ?>
</div>
<?php endif; ?>

<div class="card shadow-sm">
<div class="card-body">

<form action="penjualan_barang_simpan.php" method="post">

<!-- ANGGOTA -->
<div class="mb-3">
    <label class="form-label">Anggota</label>
    <select name="id_anggota" class="form-select">
        <option value="">-- Pilih Anggota --</option>
        <?php while ($a = mysqli_fetch_assoc($anggota)) : ?>
            <option value="<?= $a['id_anggota']; ?>">
                <?= htmlspecialchars($a['nama']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <small class="text-muted">Wajib dipilih jika pembayaran simpanan</small>
</div>

<!-- BARANG -->
<div class="mb-3">
    <label class="form-label">Barang</label>
    <select name="id_barang" id="id_barang" class="form-select" required>
        <option value="">-- Pilih Barang --</option>
        <?php while ($b = mysqli_fetch_assoc($barang)) : ?>
            <option
                value="<?= $b['id_barang']; ?>"
                data-harga="<?= $b['harga_jual']; ?>"
                data-stok="<?= $b['stok']; ?>"
                data-satuan="<?= $b['satuan']; ?>"
            >
                <?= $b['nama_barang']; ?>
                (<?= $b['stok'].' '.$b['satuan']; ?>)
            </option>
        <?php endwhile; ?>
    </select>
</div>

<!-- JUMLAH -->
<div class="mb-3">
    <label class="form-label">Jumlah</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
    <small id="infoStok" class="text-muted"></small>
</div>

<!-- HARGA OTOMATIS -->
<div class="mb-3">
    <label class="form-label">Harga Satuan</label>
    <input type="text" id="harga_view" class="form-control" readonly>
    <input type="hidden" name="harga" id="harga">
</div>

<!-- TOTAL -->
<div class="mb-3">
    <label class="form-label">Total</label>
    <input type="text" id="total" class="form-control" readonly>
</div>

<!-- METODE -->
<div class="mb-3">
    <label class="form-label">Metode Pembayaran</label>
    <select name="metode_pembayaran" class="form-select" required>
        <option value="tunai">Tunai</option>
        <option value="simpanan">Potong Simpanan</option>
    </select>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="bi bi-save"></i> Simpan Transaksi
    </button>
    <a href="penjualan_barang.php" class="btn btn-secondary">Kembali</a>
</div>

</form>

</div>
</div>

<script>
const barang    = document.getElementById('id_barang');
const jumlah    = document.getElementById('jumlah');
const harga     = document.getElementById('harga');
const hargaView = document.getElementById('harga_view');
const total     = document.getElementById('total');
const infoStok  = document.getElementById('infoStok');

function rupiah(n){
    return 'Rp ' + n.toLocaleString('id-ID');
}

function hitungTotal(){
    const h = parseFloat(harga.value) || 0;
    const j = parseInt(jumlah.value) || 0;
    total.value = rupiah(h * j);
}

/* Saat pilih barang */
barang.addEventListener('change', function(){
    const opt = this.options[this.selectedIndex];
    const h   = parseFloat(opt.dataset.harga || 0);
    const s   = opt.dataset.stok;
    const sat = opt.dataset.satuan;

    harga.value     = h;
    hargaView.value = rupiah(h);
    infoStok.innerText = `Stok tersedia: ${s} ${sat}`;

    hitungTotal();
});

/* Saat isi jumlah */
jumlah.addEventListener('input', hitungTotal);
</script>

<?php include 'layout/footer.php'; ?>
