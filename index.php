<?php

require_once "config/database.php";

// Cari file banner apapun ekstensinya
function findBannerFront(string $slot): ?string {
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        $path = 'uploads/banner/' . $slot . '.' . $ext;
        if (file_exists($path)) return $path;
    }
    return null;
}

// Fetch latest 8 products for homepage
$query = mysqli_query(
    $conn,
    "SELECT * FROM produk
     ORDER BY id DESC
     LIMIT 8"
);

$base_path  = '';
$page_title = 'Beranda';

include "includes/header.php";

?>

<!-- ================================================================
     HERO SECTION
     Banner besar (kiri) + 3 kotak promo kecil (kanan)
     Inspirasi: AmiAmi layout
     ================================================================ -->
<section class="hero-section">
    <div class="hero-layout">

        <!-- MAIN BANNER -->
        <div>
            <div class="hero-banner" id="hero-main-banner">
                <?php $bannerMain = findBannerFront('banner-main'); ?>
                <?php if ($bannerMain): ?>
                    <img src="<?= $bannerMain ?>" alt="Banner Utama AniShop">
                <?php else: ?>
                    <div class="hero-banner-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#C8C8C8" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                        </svg>
                        <span style="font-size:15px; font-weight:600; color:#9E9E9E; margin-top:8px;">
                            Letakkan banner utama di sini
                        </span>
                        <span style="font-size:13px; color:#BDBDBD; font-family:monospace;">
                            Admin → Banner
                        </span>
                        <span style="font-size:12px; color:#C8C8C8;">
                            Ukuran yang disarankan: 800 × 350px
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dot indicators -->
            <div class="hero-dots" aria-label="Slide navigation">
                <div class="hero-dot active" aria-label="Slide 1"></div>
                <div class="hero-dot" aria-label="Slide 2"></div>
                <div class="hero-dot" aria-label="Slide 3"></div>
            </div>
        </div>

        <!-- SIDEBAR PROMO BOXES (3 kotak kecil) -->
        <div class="hero-sidebar">
            <?php
            $promos = [
                [
                    'file'  => findBannerFront('promo-1'),
                    'alt'   => 'Promo 1',
                    'label' => 'Banner Promo 1',
                    'hint'  => 'Admin → Banner',
                    'size'  => '248 × 110px',
                    'id'    => 'promo-box-1',
                ],
                [
                    'file'  => findBannerFront('promo-2'),
                    'alt'   => 'Promo 2',
                    'label' => 'Banner Promo 2',
                    'hint'  => 'Admin → Banner',
                    'size'  => '248 × 110px',
                    'id'    => 'promo-box-2',
                ],
                [
                    'file'  => findBannerFront('promo-3'),
                    'alt'   => 'Promo 3',
                    'label' => 'Banner Promo 3',
                    'hint'  => 'Admin → Banner',
                    'size'  => '248 × 110px',
                    'id'    => 'promo-box-3',
                ],
            ];

            foreach ($promos as $promo):
            ?>
            <div class="promo-box" id="<?= $promo['id'] ?>">
                <?php if (file_exists($promo['file'])): ?>
                    <img src="<?= $promo['file'] ?>" alt="<?= htmlspecialchars($promo['alt']) ?>">
                <?php else: ?>
                    <div class="promo-box-placeholder">
                        <div>
                            <div style="font-weight:600; color:#9E9E9E; margin-bottom:2px;">
                                <?= $promo['label'] ?>
                            </div>
                            <div style="font-size:11px; color:#C8C8C8; font-family:monospace; margin-bottom:2px;">
                                <?= $promo['hint'] ?>
                            </div>
                            <div style="font-size:10px; color:#BDBDBD;">
                                <?= $promo['size'] ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ================================================================
     PRODUK TERBARU
     ================================================================ -->
<main class="main-content">
    <div class="container">

        <div class="section-header">
            <h1 class="section-title">Produk Terbaru</h1>
            <a href="produk.php" class="section-link">Lihat Semua →</a>
        </div>

        <?php if (mysqli_num_rows($query) > 0): ?>
        <div class="product-grid" id="homepage-product-grid">

            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <article class="product-card" id="product-<?= $row['id'] ?>">

                <!-- Image + SAMPLE stamp -->
                <a href="detail.php?id=<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="product-image-wrap">
                        <?php if ($row['gambar']): ?>
                            <img
                                src="uploads/produk/<?= htmlspecialchars($row['gambar']) ?>"
                                alt="<?= htmlspecialchars($row['nama']) ?>"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="product-img-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#C8C8C8" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <!-- SAMPLE STAMP hover overlay -->
                        <div class="stamp-overlay" aria-hidden="true">
                            <div class="stamp-text">SAMPLE</div>
                        </div>
                    </div>
                </a>

                <!-- Product Info -->
                <div class="product-info">
                    <div class="product-category">
                        <?= htmlspecialchars($row['kategori']) ?>
                    </div>
                    <a href="detail.php?id=<?= $row['id'] ?>" class="product-name">
                        <?= htmlspecialchars($row['nama']) ?>
                    </a>
                    <div class="product-price">
                        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                    </div>
                    <div class="product-actions">
                        <a
                            href="detail.php?id=<?= $row['id'] ?>"
                            class="btn btn-primary btn-sm"
                            style="flex:1;"
                            id="detail-btn-<?= $row['id'] ?>"
                        >
                            Lihat Detail
                        </a>
                        <button
                            class="btn btn-outline btn-sm"
                            title="Tambah ke Wishlist"
                            id="wishlist-prod-<?= $row['id'] ?>"
                            onclick="alert('Fitur wishlist segera hadir! 🌸')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </article>
            <?php endwhile; ?>

        </div>

        <!-- CTA ke halaman produk lengkap -->
        <div style="text-align:center; margin-top:48px;">
            <a href="produk.php" class="btn btn-outline btn-lg" id="see-all-products-btn">
                Lihat Semua Produk →
            </a>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <h3>Belum Ada Produk</h3>
            <p>Silakan tambahkan produk melalui panel admin.</p>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php include "includes/footer.php"; ?>