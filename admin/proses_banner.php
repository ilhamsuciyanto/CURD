<?php
/**
 * admin/proses_banner.php
 * Menangani upload banner utama dan promo.
 */
session_start();
require_once '../includes/auth.php';

$slot = $_POST['slot'] ?? '';

$allowed_slots = [
    'banner-main' => ['file' => '../uploads/banner/banner-main.jpg', 'label' => 'Banner Utama'],
    'promo-1'     => ['file' => '../uploads/banner/promo-1.jpg',     'label' => 'Banner Promo 1'],
    'promo-2'     => ['file' => '../uploads/banner/promo-2.jpg',     'label' => 'Banner Promo 2'],
    'promo-3'     => ['file' => '../uploads/banner/promo-3.jpg',     'label' => 'Banner Promo 3'],
];

if (!array_key_exists($slot, $allowed_slots)) {
    $_SESSION['error'] = 'Slot banner tidak valid.';
    header('Location: banner.php');
    exit;
}

$target = $allowed_slots[$slot];

// Pastikan folder ada
$dir = '../uploads/banner/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Cek ada file yang dikirim
if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
    $_SESSION['error'] = 'Pilih file gambar terlebih dahulu.';
    header('Location: banner.php');
    exit;
}

if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Terjadi kesalahan saat upload (kode: ' . $_FILES['gambar']['error'] . ').';
    header('Location: banner.php');
    exit;
}

$file = $_FILES['gambar'];
$ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    $_SESSION['error'] = 'Format tidak diizinkan. Gunakan JPG, PNG, atau WebP.';
    header('Location: banner.php');
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    $_SESSION['error'] = 'Ukuran file maksimal 5 MB.';
    header('Location: banner.php');
    exit;
}

// Hapus file lama dulu jika ada (semua ekstensi)
foreach (['jpg', 'jpeg', 'png', 'webp'] as $e) {
    $old = $dir . $slot . '.' . $e;
    if (file_exists($old)) {
        unlink($old);
    }
}

// Simpan dengan nama slot + ekstensi asli
$filename = $slot . '.' . $ext;
move_uploaded_file($file['tmp_name'], $dir . $filename);

// Update index.php jika ekstensinya bukan .jpg agar path sesuai
// Kita simpan nama file aktual ke session supaya banner.php bisa baca
$_SESSION['success'] = "{$target['label']} berhasil diperbarui!";
header('Location: banner.php');
exit;
