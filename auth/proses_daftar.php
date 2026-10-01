<?php

session_start();

require_once "../config/database.php";

// ----------------------------------------------------------------
// Ambil & bersihkan input
// ----------------------------------------------------------------
$nama       = trim($_POST['nama']       ?? '');
$email      = trim($_POST['email']      ?? '');
$password   = $_POST['password']        ?? '';
$konfirmasi = $_POST['konfirmasi']      ?? '';

// ----------------------------------------------------------------
// Validasi — simpan nilai input agar tidak hilang kalau error
// ----------------------------------------------------------------
function redirect_error(string $pesan, string $nama, string $email): never
{
    $_SESSION['error']      = $pesan;
    $_SESSION['form_nama']  = $nama;
    $_SESSION['form_email'] = $email;
    header("Location: daftar.php");
    exit;
}

if ($nama === '' || $email === '' || $password === '' || $konfirmasi === '') {
    redirect_error("Semua field wajib diisi.", $nama, $email);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_error("Format e-mail tidak valid.", $nama, $email);
}

if (strlen($password) < 6) {
    redirect_error("Password minimal 6 karakter.", $nama, $email);
}

if (strlen($password) > 32) {
    redirect_error("Password maksimal 32 karakter.", $nama, $email);
}

if ($password !== $konfirmasi) {
    redirect_error("Konfirmasi password tidak cocok.", $nama, $email);
}

// ----------------------------------------------------------------
// Cek apakah email sudah terdaftar
// ----------------------------------------------------------------
$email_safe = mysqli_real_escape_string($conn, $email);

$cek = mysqli_query(
    $conn,
    "SELECT id FROM user WHERE email = '$email_safe' LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    redirect_error("E-mail sudah terdaftar. Silakan gunakan e-mail lain.", $nama, $email);
}

// ----------------------------------------------------------------
// Simpan ke database
// ----------------------------------------------------------------
$nama_safe     = mysqli_real_escape_string($conn, $nama);
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$insert = mysqli_query(
    $conn,
    "INSERT INTO user (nama, email, password)
     VALUES ('$nama_safe', '$email_safe', '$password_hash')"
);

if ($insert) {
    $_SESSION['success'] = "Akun berhasil dibuat! Silakan log in.";
    header("Location: login.php");
    exit;
} else {
    redirect_error("Terjadi kesalahan. Silakan coba lagi.", $nama, $email);
}
