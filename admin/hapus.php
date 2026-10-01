<?php
session_start();
require_once '../includes/auth.php';
require_once '../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: produk.php');
    exit;
}

// Ambil data foto (utama + sample) sekaligus
$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT gambar, gambar_sample FROM produk WHERE id = $id")
);

if (!$data) {
    $_SESSION['error'] = 'Produk tidak ditemukan.';
    header('Location: produk.php');
    exit;
}

// Hapus file foto utama
if ($data['gambar'] && file_exists('../uploads/produk/' . $data['gambar'])) {
    unlink('../uploads/produk/' . $data['gambar']);
}

// Hapus file foto sample
if (!empty($data['gambar_sample']) && file_exists('../uploads/produk/' . $data['gambar_sample'])) {
    unlink('../uploads/produk/' . $data['gambar_sample']);
}

// Hapus dari database
$ok = mysqli_query($conn, "DELETE FROM produk WHERE id = $id");

$_SESSION[$ok ? 'success' : 'error'] = $ok
    ? 'Produk berhasil dihapus.'
    : 'Produk gagal dihapus. Silakan coba lagi.';

header('Location: produk.php');
exit;