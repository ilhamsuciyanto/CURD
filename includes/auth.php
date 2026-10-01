<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {

    $_SESSION['error'] =
        "Silakan login terlebih dahulu.";

    header("Location: ../auth/login.php");

    exit;
}