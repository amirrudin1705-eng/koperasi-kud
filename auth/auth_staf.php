<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'staf') {
    header("Location: ../auth/login.php");
    exit;
}
