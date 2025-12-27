<?php
require '../auth/auth_staf.php';
require '../config/database.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM angsuran WHERE id_angsuran='$id'");
header("Location: angsuran.php");
exit;
