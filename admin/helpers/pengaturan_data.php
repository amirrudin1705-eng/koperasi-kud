<?php
if (!isset($conn)) {
    die('Koneksi tidak tersedia');
}

/* ===============================
   DATA ADMIN
================================ */
$id_admin = $_SESSION['id_user'];

$qAdmin = mysqli_query($conn, "
    SELECT nama, email
    FROM users
    WHERE id_user = '$id_admin'
");
$dataAdmin = mysqli_fetch_assoc($qAdmin);

/* ===============================
   DATA PENGATURAN KOPERASI
================================ */
$qSetting = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
$setting = mysqli_fetch_assoc($qSetting);

/* ===============================
   UPDATE AKUN ADMIN
================================ */
if (isset($_POST['update_admin'])) {
    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    mysqli_query($conn, "
        UPDATE users SET
            nama='$nama',
            email='$email'
        WHERE id_user='$id_admin'
    ");

    header("Location: pengaturan.php");
    exit;
}

/* ===============================
   UPDATE PENGATURAN KOPERASI
================================ */
if (isset($_POST['update_koperasi'])) {
    $nama_koperasi = $_POST['nama_koperasi'];
    $alamat        = $_POST['alamat'];
    $telepon       = $_POST['telepon'];
    $email         = $_POST['email_koperasi'];

    if ($setting) {
        mysqli_query($conn, "
            UPDATE pengaturan SET
                nama_koperasi='$nama_koperasi',
                alamat='$alamat',
                telepon='$telepon',
                email='$email'
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO pengaturan
            (nama_koperasi, alamat, telepon, email)
            VALUES
            ('$nama_koperasi','$alamat','$telepon','$email')
        ");
    }

    header("Location: pengaturan.php");
    exit;
}

/* ===============================
   UPDATE PENGATURAN PINJAMAN
================================ */
if (isset($_POST['update_pinjaman'])) {
    $bunga = $_POST['bunga_default'];
    $tenor = $_POST['tenor_maks'];

    if ($setting) {
        // JIKA SUDAH ADA DATA
        mysqli_query($conn, "
            UPDATE pengaturan SET
                bunga_default='$bunga',
                tenor_maks='$tenor'
        ");
    } else {
        // JIKA BELUM ADA DATA
        mysqli_query($conn, "
            INSERT INTO pengaturan
            (bunga_default, tenor_maks)
            VALUES
            ('$bunga', '$tenor')
        ");
    }

    echo "<script>
        alert('Pengaturan pinjaman berhasil disimpan');
        window.location='pengaturan.php';
    </script>";
    exit;
}
