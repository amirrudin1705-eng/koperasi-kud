<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("Asia/Jakarta");
define("BASE_URL", "http://localhost/kud-simpan-pinjam");


define("APP_NAME", "KUD Simpan Pinjam");