<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM simpanan WHERE id_simpanan='$id'");
header("Location: simpanan.php");
exit;
