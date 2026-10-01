<?php
/**
 * includes/header.php
 * Reusable site header (topbar + navbar)
 * 
 * Variables expected before including:
 *   $base_path  (string) — relative path to root, e.g. '' or '../'
 *   $page_title (string) — page title prefix (optional)
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AniShop — Toko merchandise anime terlengkap. Figure, goods, apparel, manga dan lebih banyak.">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — AniShop' : 'AniShop — Toko Merchandise Anime' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_path ?>assets/style.css">
</head>
<body>

<!-- ================================================================
     TOPBAR — Logo · Search · Account Actions
     ================================================================ -->
<header class="topbar">
    <div class="topbar-inner">

        <!-- Logo -->
        <a href="<?= $base_path ?>index.php" class="logo" aria-label="AniShop — Beranda">
            <div class="logo-text">
                <span class="ani">Ani</span><span class="shop">Shop</span>
            </div>
            <?php
            $charImg = __DIR__ . '/../assets/images/hero-char.png';
            if (file_exists($charImg)):
            ?>
                <img
                    src="<?= $base_path ?>assets/images/hero-char.png?v=<?= filemtime($charImg) ?>"
                    alt="AniShop mascot"
                    class="logo-char"
                >
            <?php endif; ?>
        </a>

        <!-- Search Bar -->
        <div class="search-wrapper">
            <form class="search-form" action="<?= $base_path ?>produk.php" method="GET" role="search">
                <select class="search-category" name="kategori" aria-label="Pilih kategori">
                    <option value="">Semua</option>
                    <option value="Figure"   <?= (($_GET['kategori'] ?? '') === 'Figure')  ? 'selected' : '' ?>>Figure</option>
                    <option value="Goods"    <?= (($_GET['kategori'] ?? '') === 'Goods')   ? 'selected' : '' ?>>Goods</option>
                    <option value="Apparel"  <?= (($_GET['kategori'] ?? '') === 'Apparel') ? 'selected' : '' ?>>Apparel</option>
                    <option value="Manga"    <?= (($_GET['kategori'] ?? '') === 'Manga')   ? 'selected' : '' ?>>Manga</option>
                </select>
                <input
                    id="search-input"
                    type="search"
                    name="keyword"
                    class="search-input"
                    placeholder="Cari anime, figure, goods..."
                    value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
                    autocomplete="off"
                >
                <button type="submit" class="search-btn" aria-label="Cari">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Account Actions -->
        <nav class="header-actions" aria-label="Akun & keranjang">

            <?php if (isset($_SESSION['admin_id'])): ?>
                <!-- Admin mode -->
                <a href="<?= $base_path ?>admin/index.php" class="header-action" id="admin-btn" title="Panel Admin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Admin</span>
                </a>
                <a href="<?= $base_path ?>admin/logout.php" class="header-action" id="logout-btn" title="Logout" onclick="return confirm('Apakah kamu yakin mau logout?');">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <!-- Guest mode -->
                <a href="<?= $base_path ?>auth/login.php" class="header-action" id="login-btn" title="Login / Daftar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    <span>Login</span>
                </a>
            <?php endif; ?>

            <button class="header-action" id="wishlist-btn" title="Wishlist" onclick="alert('Fitur wishlist segera hadir! 🌸')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
                <span>Wishlist</span>
            </button>

            <button class="header-action" id="cart-btn" title="Keranjang" onclick="alert('Fitur keranjang segera hadir! 🛒')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
                <span>Keranjang</span>
            </button>

        </nav>
    </div>
</header>

<!-- ================================================================
     NAVBAR — Category Links
     ================================================================ -->
<nav class="navbar" aria-label="Navigasi kategori">
    <div class="navbar-inner">
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        $currentKat  = $_GET['kategori'] ?? '';
        ?>
        <a href="<?= $base_path ?>index.php"
           class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
            Beranda
        </a>
        <a href="<?= $base_path ?>produk.php"
           class="nav-link <?= ($currentPage === 'produk.php' && $currentKat === '') ? 'active' : '' ?>">
            Semua Produk
        </a>
        <a href="<?= $base_path ?>produk.php?kategori=Figure"
           class="nav-link <?= $currentKat === 'Figure' ? 'active' : '' ?>">
            Figure
        </a>
        <a href="<?= $base_path ?>produk.php?kategori=Goods"
           class="nav-link <?= $currentKat === 'Goods' ? 'active' : '' ?>">
            Goods
        </a>
        <a href="<?= $base_path ?>produk.php?kategori=Apparel"
           class="nav-link <?= $currentKat === 'Apparel' ? 'active' : '' ?>">
            Apparel
        </a>
        <a href="<?= $base_path ?>produk.php?kategori=Manga"
           class="nav-link <?= $currentKat === 'Manga' ? 'active' : '' ?>">
            Manga
        </a>
        <a href="<?= $base_path ?>tentang.php"
           class="nav-link <?= $currentPage === 'tentang.php' ? 'active' : '' ?>">
            Tentang
        </a>
        <a href="<?= $base_path ?>produk.php?sale=1" class="nav-link highlight">
            ★ Sale
        </a>
    </div>
</nav>
