<?php

require_once "config/database.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query  = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    header("Location: produk.php");
    exit;
}

// Stock status
if ($produk['stok'] <= 0) {
    $stok_class = 'out-stock';
    $stok_label = 'Habis';
} elseif ($produk['stok'] <= 5) {
    $stok_class = 'low-stock';
    $stok_label = 'Hampir habis (' . $produk['stok'] . ' tersisa)';
} else {
    $stok_class = 'in-stock';
    $stok_label = 'Tersedia (' . $produk['stok'] . ')';
}

$base_path  = '';
$page_title = htmlspecialchars($produk['nama']);

include "includes/header.php";

?>

<main>
    <div class="container">

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="index.php">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <a href="produk.php">Produk</a>
            <?php if ($produk['kategori']): ?>
            <span class="breadcrumb-sep">›</span>
            <a href="produk.php?kategori=<?= urlencode($produk['kategori']) ?>">
                <?= htmlspecialchars($produk['kategori']) ?>
            </a>
            <?php endif; ?>
            <span class="breadcrumb-sep">›</span>
            <span class="breadcrumb-current">
                <?= htmlspecialchars($produk['nama']) ?>
            </span>
        </nav>

        <!-- Detail layout: image (left) + info (right) -->
        <div class="detail-layout">

            <!-- LEFT: Product Image -->
            <div>
                <div class="detail-image-main" id="detail-image-wrap">
                    <?php if ($produk['gambar']): ?>
                        <img
                            src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>"
                            alt="<?= htmlspecialchars($produk['nama']) ?>"
                            id="detail-main-img"
                        >
                    <?php else: ?>
                        <div class="detail-image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24" stroke="#C8C8C8" stroke-width="0.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Back link -->
                <div style="margin-top:16px;">
                    <a href="produk.php" class="btn btn-ghost btn-sm" id="back-to-produk-btn">
                        ← Kembali ke Produk
                    </a>
                </div>
            </div>

            <!-- RIGHT: Product Info -->
            <div class="detail-info">

                <!-- Category badge -->
                <?php if ($produk['kategori']): ?>
                <a
                    href="produk.php?kategori=<?= urlencode($produk['kategori']) ?>"
                    class="detail-category-badge"
                    id="detail-category-link"
                >
                    <?= htmlspecialchars($produk['kategori']) ?>
                </a>
                <?php endif; ?>

                <!-- Product name -->
                <h1 class="detail-name" id="detail-product-name">
                    <?= htmlspecialchars($produk['nama']) ?>
                </h1>

                <!-- Price -->
                <div class="detail-price" id="detail-product-price">
                    Rp <?= number_format($produk['harga'], 0, ',', '.') ?>
                </div>

                <hr class="detail-divider">

                <!-- Metadata -->
                <div class="detail-meta">
                    <div class="detail-meta-row">
                        <span class="detail-meta-label">Kategori</span>
                        <span class="detail-meta-value">
                            <?= htmlspecialchars($produk['kategori'] ?: '—') ?>
                        </span>
                    </div>
                    <div class="detail-meta-row">
                        <span class="detail-meta-label">Stok</span>
                        <span class="detail-meta-value <?= $stok_class ?>">
                            <?= $stok_label ?>
                        </span>
                    </div>
                </div>

                <hr class="detail-divider">

                <!-- Description -->
                <?php if ($produk['deskripsi']): ?>
                <p class="detail-desc" id="detail-description">
                    <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?>
                </p>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="detail-actions">
                    <div class="detail-actions-row">
                        <button
                            class="btn btn-primary btn-lg"
                            style="flex:1;"
                            id="buy-now-btn"
                            onclick="alert('Fitur pembelian segera hadir! 🛒')"
                            <?= $produk['stok'] <= 0 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>
                        >
                            <?= $produk['stok'] > 0 ? 'Beli Sekarang' : 'Stok Habis' ?>
                        </button>
                        <button
                            class="btn btn-outline btn-lg"
                            id="add-to-cart-btn"
                            onclick="alert('Fitur keranjang segera hadir! 🛒')"
                            <?= $produk['stok'] <= 0 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                            </svg>
                            Keranjang
                        </button>
                    </div>
                    <button
                        class="btn btn-ghost btn-lg btn-full"
                        id="add-wishlist-detail-btn"
                        onclick="alert('Fitur wishlist segera hadir! 🌸')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                        </svg>
                        Tambah ke Wishlist
                    </button>
                </div>

            </div>
        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>