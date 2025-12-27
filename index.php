<?php
require_once 'config/config.php';

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

switch ($_SESSION['role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    case 'staf':
        header("Location: staf/dashboard.php");
        break;
    case 'anggota':
        header("Location: anggota/dashboard.php");
        break;
    default:
        session_destroy();
        header("Location: auth/login.php");
        break;
}
exit;

