<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "toko_kue";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8mb4 agar mendukung karakter spesial, emoji, dan teks jepang
mysqli_set_charset($conn, "utf8mb4");