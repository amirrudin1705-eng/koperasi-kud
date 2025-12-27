<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>
        alert('Silakan pilih file foto terlebih dahulu!');
        window.location='../profil.php';
    </script>";
    exit;
}

$allowedExt = ['jpg', 'jpeg', 'png'];
$maxSize    = 2 * 1024 * 1024; 

$namaAsli = $_FILES['foto']['name'];
$tmpFile  = $_FILES['foto']['tmp_name'];
$fileSize = $_FILES['foto']['size'];

$ext = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    echo "<script>
        alert('Format file harus JPG atau PNG!');
        window.location='../profil.php';
    </script>";
    exit;
}

if ($fileSize > $maxSize) {
    echo "<script>
        alert('Ukuran file maksimal 2MB!');
        window.location='../profil.php';
    </script>";
    exit;
}

$folderTujuan = '../../assets/uploads/profile/';
if (!is_dir($folderTujuan)) {
    mkdir($folderTujuan, 0755, true);
}

/* =========================
   Paksa jadi JPG (AMAN)
   ========================= */
$namaFileBaru = 'user_' . $id_user . '.jpg';
$pathFile     = $folderTujuan . $namaFileBaru;

if ($ext === 'png') {
    $img = imagecreatefrompng($tmpFile);
} else {
    $img = imagecreatefromjpeg($tmpFile);
}

if (!$img) {
    echo "<script>
        alert('Gagal memproses gambar!');
        window.location='../profil.php';
    </script>";
    exit;
}

imagejpeg($img, $pathFile, 90);
imagedestroy($img);

/* =========================
   Update database
   ========================= */
$update = mysqli_query($conn, "
    UPDATE users 
    SET foto = '$namaFileBaru'
    WHERE id_user = '$id_user'
");

if (!$update) {
    echo "<script>
        alert('Gagal menyimpan data ke database!');
        window.location='../profil.php';
    </script>";
    exit;
}

echo "<script>
    alert('Foto profil berhasil diperbarui');
    window.location='../profil.php';
</script>";
exit;
