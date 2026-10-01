<?php
/**
 * admin/proses_produk.php
 * Menangani tambah dan edit produk.
 * Harus dipanggil via POST dari tambah.php atau edit.php.
 */

require_once '../includes/auth.php';
require_once '../config/database.php';

// session_start() sudah dipanggil di auth.php

$aksi = $_POST['aksi'] ?? '';

// ============================================================
// HELPER — validasi & upload satu file gambar
// Mengembalikan: nama file (string) | null (tidak ada file) | false (error)
// ============================================================
function uploadGambar(string $field, string &$error): string|null|false
{
    // Tidak ada file dikirim atau tidak dipilih — opsional, aman
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    // Ada file tapi terjadi error upload lain
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $error = "Terjadi kesalahan saat mengupload '$field' (kode: {$_FILES[$field]['error']}).";
        return false;
    }

    $file      = $_FILES[$field];
    $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        $error = "Format gambar tidak diperbolehkan. Gunakan JPG, PNG, atau WebP.";
        return false;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $error = "Ukuran gambar maksimal 2 MB.";
        return false;
    }

    $filename = uniqid('img_') . '.' . $ext;
    move_uploaded_file($file['tmp_name'], '../uploads/produk/' . $filename);
    return $filename;
}

// ============================================================
// Helper — hapus file gambar jika ada
// ============================================================
function hapusGambar(?string $filename): void
{
    if ($filename && file_exists('../uploads/produk/' . $filename)) {
        unlink('../uploads/produk/' . $filename);
    }
}

// ============================================================
// TAMBAH PRODUK
// ============================================================
if ($aksi === 'tambah') {

    $nama      = trim($_POST['nama']      ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga     = trim($_POST['harga']     ?? '');
    $stok      = trim($_POST['stok']      ?? '');

    // — Validasi —
    if ($nama === '' || $kategori === '' || $deskripsi === '' || $harga === '' || $stok === '') {
        $_SESSION['error'] = 'Semua field wajib diisi.';
        header('Location: tambah.php');
        exit;
    }

    if (!is_numeric($harga) || (float)$harga < 0) {
        $_SESSION['error'] = 'Harga harus berupa angka positif.';
        header('Location: tambah.php');
        exit;
    }

    if (!is_numeric($stok) || (int)$stok < 0) {
        $_SESSION['error'] = 'Stok harus berupa angka 0 atau lebih.';
        header('Location: tambah.php');
        exit;
    }

    // — Upload foto utama —
    $uploadError = '';
    $fotoUtama   = uploadGambar('gambar', $uploadError);
    if ($fotoUtama === false) {
        $_SESSION['error'] = $uploadError;
        header('Location: tambah.php');
        exit;
    }

    // — Upload foto sample —
    $fotoSample = uploadGambar('gambar_sample', $uploadError);
    if ($fotoSample === false) {
        hapusGambar($fotoUtama); // rollback foto utama
        $_SESSION['error'] = $uploadError;
        header('Location: tambah.php');
        exit;
    }

    // — Simpan ke database —
    $nama_s      = mysqli_real_escape_string($conn, $nama);
    $kategori_s  = mysqli_real_escape_string($conn, $kategori);
    $deskripsi_s = mysqli_real_escape_string($conn, $deskripsi);
    $foto_val    = $fotoUtama  ? "'" . mysqli_real_escape_string($conn, $fotoUtama)  . "'" : 'NULL';
    $sample_val  = $fotoSample ? "'" . mysqli_real_escape_string($conn, $fotoSample) . "'" : 'NULL';

    $ok = mysqli_query($conn,
        "INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, gambar_sample)
         VALUES ('$nama_s', '$kategori_s', '$deskripsi_s', '$harga', '$stok', $foto_val, $sample_val)"
    );

    $_SESSION[$ok ? 'success' : 'error'] = $ok
        ? 'Produk berhasil ditambahkan.'
        : 'Produk gagal ditambahkan. Silakan coba lagi.';

    header('Location: produk.php');
    exit;
}

// ============================================================
// EDIT PRODUK
// ============================================================
if ($aksi === 'edit') {

    $id        = (int) ($_POST['id'] ?? 0);
    $nama      = trim($_POST['nama']      ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga     = trim($_POST['harga']     ?? '');
    $stok      = trim($_POST['stok']      ?? '');

    if ($id <= 0) {
        header('Location: produk.php');
        exit;
    }

    // — Validasi —
    if ($nama === '' || $kategori === '' || $deskripsi === '' || $harga === '' || $stok === '') {
        $_SESSION['error'] = 'Semua field wajib diisi.';
        header("Location: edit.php?id=$id");
        exit;
    }

    if (!is_numeric($harga) || (float)$harga < 0) {
        $_SESSION['error'] = 'Harga harus berupa angka positif.';
        header("Location: edit.php?id=$id");
        exit;
    }

    if (!is_numeric($stok) || (int)$stok < 0) {
        $_SESSION['error'] = 'Stok harus berupa angka 0 atau lebih.';
        header("Location: edit.php?id=$id");
        exit;
    }

    // — Ambil data foto lama (1 query) —
    $oldRow = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT gambar, gambar_sample FROM produk WHERE id = $id")
    );

    if (!$oldRow) {
        $_SESSION['error'] = 'Produk tidak ditemukan.';
        header('Location: produk.php');
        exit;
    }

    $fotoUtama  = $oldRow['gambar'];
    $fotoSample = $oldRow['gambar_sample'];

    // — Upload foto utama baru (jika ada) —
    $uploadError = '';
    $fotoBaru    = uploadGambar('gambar', $uploadError);

    if ($fotoBaru === false) {
        $_SESSION['error'] = $uploadError;
        header("Location: edit.php?id=$id");
        exit;
    }

    if ($fotoBaru !== null) {
        hapusGambar($fotoUtama); // hapus foto lama
        $fotoUtama = $fotoBaru;
    }

    // — Upload foto sample baru (jika ada) —
    $sampleBaru = uploadGambar('gambar_sample', $uploadError);

    if ($sampleBaru === false) {
        // Rollback foto utama baru jika sudah terlanjur diupload
        if ($fotoBaru !== null) {
            hapusGambar($fotoBaru);
        }
        $_SESSION['error'] = $uploadError;
        header("Location: edit.php?id=$id");
        exit;
    }

    if ($sampleBaru !== null) {
        hapusGambar($fotoSample); // hapus sample lama
        $fotoSample = $sampleBaru;
    }

    // — Update database —
    $nama_s      = mysqli_real_escape_string($conn, $nama);
    $kategori_s  = mysqli_real_escape_string($conn, $kategori);
    $deskripsi_s = mysqli_real_escape_string($conn, $deskripsi);
    $foto_val    = $fotoUtama  ? "'" . mysqli_real_escape_string($conn, $fotoUtama)  . "'" : 'NULL';
    $sample_val  = $fotoSample ? "'" . mysqli_real_escape_string($conn, $fotoSample) . "'" : 'NULL';

    $ok = mysqli_query($conn,
        "UPDATE produk SET
             nama          = '$nama_s',
             kategori      = '$kategori_s',
             deskripsi     = '$deskripsi_s',
             harga         = '$harga',
             stok          = '$stok',
             gambar        = $foto_val,
             gambar_sample = $sample_val
         WHERE id = $id"
    );

    $_SESSION[$ok ? 'success' : 'error'] = $ok
        ? 'Produk berhasil diperbarui.'
        : 'Produk gagal diperbarui. Silakan coba lagi.';

    header('Location: produk.php');
    exit;
}

// Aksi tidak dikenal — redirect ke produk
header('Location: produk.php');
exit;