<?php
session_start();
require_once '../includes/auth.php';

$slot = $_GET['slot'] ?? '';

$allowed = ['banner-main', 'promo-1', 'promo-2', 'promo-3'];

if (!in_array($slot, $allowed)) {
    $_SESSION['error'] = 'Slot banner tidak valid.';
    header('Location: banner.php');
    exit;
}

$deleted = false;
foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
    $path = '../uploads/banner/' . $slot . '.' . $ext;
    if (file_exists($path)) {
        unlink($path);
        $deleted = true;
    }
}

$_SESSION[$deleted ? 'success' : 'error'] = $deleted
    ? 'Banner berhasil dihapus.'
    : 'Banner tidak ditemukan.';

header('Location: banner.php');
exit;
