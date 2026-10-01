<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$total_produk = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk")
);

$total_kategori = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) AS total FROM produk")
);

$stok_habis = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk WHERE stok = 0")
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — AniShop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body class="admin-page-bg">

<!-- Admin Topbar -->
<header class="admin-topbar">
    <nav class="admin-nav">
        <div class="admin-brand">
            <span class="ani">Ani</span><span class="shop">Shop</span>
            <span style="font-size:11px; opacity:0.6; display:block; font-weight:500; line-height:1;">Admin Panel</span>
        </div>
        <a href="index.php"   class="admin-nav-link active">Dashboard</a>
        <a href="produk.php"  class="admin-nav-link">Produk</a>
        <a href="tambah.php"  class="admin-nav-link">+ Tambah</a>
        <a href="banner.php"  class="admin-nav-link">Banner</a>
        <a href="../index.php" class="admin-nav-link">← Lihat Website</a>
        <a href="logout.php"  class="admin-nav-link danger" onclick="return confirm('Apakah kamu yakin mau logout?');">Logout</a>
    </nav>
</header>

<!-- Content -->
<div class="container" style="padding-top: var(--s6); padding-bottom: var(--s8);">

    <div style="margin-bottom: var(--s5);">
        <h1 style="font-size: var(--text-2xl); font-weight: 700; margin-bottom: 4px;">
            Dashboard
        </h1>
        <p style="color: var(--text-muted); font-size: var(--text-lg);">
            Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></strong>
        </p>
    </div>

    <!-- Stats -->
    <div class="admin-stat-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Total Produk</div>
            <div class="admin-stat-value accent"><?= $total_produk['total'] ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Kategori</div>
            <div class="admin-stat-value"><?= $total_kategori['total'] ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Stok Habis</div>
            <div class="admin-stat-value warning"><?= $stok_habis['total'] ?></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
        <div class="admin-card-title">Manajemen Produk</div>
        <p style="color: var(--text-muted); margin-bottom: var(--s3); font-size: var(--text-lg);">
            Kelola katalog produk AniShop — tambah, edit, atau hapus produk.
        </p>
        <div style="display:flex; gap: var(--s2); flex-wrap:wrap;">
            <a href="produk.php"  class="btn btn-primary">Lihat Semua Produk</a>
            <a href="tambah.php"  class="btn btn-outline">+ Tambah Produk Baru</a>
        </div>
    </div>

</div>

</body>
</html>