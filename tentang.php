<?php

$base_path  = '';
$page_title = 'Tentang Kami';

include "includes/header.php";

?>

<main class="main-content">
    <div class="container">

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="index.php">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <span class="breadcrumb-current">Tentang Kami</span>
        </nav>

        <div class="about-section">
            <h1>Tentang AniShop</h1>

            <p>
                AniShop adalah toko merchandise anime terpercaya yang menghadirkan
                koleksi figure, goods, apparel, dan manga pilihan untuk para pecinta
                anime di seluruh Indonesia.
            </p>

            <p>
                Kami berkomitmen untuk menyediakan produk berkualitas tinggi
                dengan harga yang terjangkau, dikirim langsung ke rumah kamu.
                Setiap produk yang kami jual dipilih dengan cermat untuk memastikan
                keaslian dan kualitas terbaik.
            </p>

            <p>
                Dari figure skala tinggi, nendoroid, hingga merchandise karakter
                favorit kamu — AniShop adalah tempatnya.
            </p>
        </div>

        <!-- Feature cards -->
        <div class="about-grid">

            <div class="about-card">
                <div class="about-card-icon">📦</div>
                <h3>Produk Original</h3>
                <p>Semua produk dijamin original dan berlisensi resmi dari produsen terpercaya.</p>
            </div>

            <div class="about-card">
                <div class="about-card-icon">🚚</div>
                <h3>Pengiriman Cepat</h3>
                <p>Pengiriman ke seluruh Indonesia dengan layanan terpercaya dan aman.</p>
            </div>

            <div class="about-card">
                <div class="about-card-icon">🌸</div>
                <h3>Komunitas Anime</h3>
                <p>Bergabung dengan ribuan otaku Indonesia yang telah mempercayai AniShop.</p>
            </div>

        </div>

        <div style="margin-top: 48px;">
            <a href="produk.php" class="btn btn-primary btn-lg" id="explore-products-btn">
                Jelajahi Produk →
            </a>
        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>