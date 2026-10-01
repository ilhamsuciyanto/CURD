<?php

require_once "config/database.php";

// — Filters from GET —
$keyword  = trim($_GET['keyword']  ?? '');
$kategori = trim($_GET['kategori'] ?? '');

// — Build query —
$where_parts = [];

if ($keyword !== '') {
    $kw_safe = mysqli_real_escape_string($conn, $keyword);
    $where_parts[] = "(nama LIKE '%$kw_safe%' OR deskripsi LIKE '%$kw_safe%')";
}

if ($kategori !== '') {
    $kat_safe = mysqli_real_escape_string($conn, $kategori);
    $where_parts[] = "kategori = '$kat_safe'";
}

$where_clause = count($where_parts) > 0
    ? 'WHERE ' . implode(' AND ', $where_parts)
    : '';

$query = mysqli_query(
    $conn,
    "SELECT * FROM produk
     $where_clause
     ORDER BY id DESC"
);

$total = mysqli_num_rows($query);

$base_path  = '';
$page_title = $kategori ?: ($keyword ? 'Pencarian' : 'Semua Produk');

include "includes/header.php";

?>

<!-- ================================================================
     SEARCH & FILTER BAR
     ================================================================ -->
<div class="search-bar-section">
    <div class="container">
        <form method="GET" id="filter-form" class="search-bar-inner">

            <!-- Keyword input -->
            <input
                type="search"
                name="keyword"
                class="form-control"
                id="produk-keyword"
                placeholder="Cari nama produk..."
                value="<?= htmlspecialchars($keyword) ?>"
                style="max-width:360px; height:44px;"
            >

            <!-- Kategori filter -->
            <select
                name="kategori"
                class="filter-select"
                id="produk-kategori"
                onchange="this.form.submit()"
            >
                <option value="">Semua Kategori</option>
                <option value="Figure"  <?= $kategori === 'Figure'  ? 'selected' : '' ?>>Figure</option>
                <option value="Goods"   <?= $kategori === 'Goods'   ? 'selected' : '' ?>>Goods</option>
                <option value="Apparel" <?= $kategori === 'Apparel' ? 'selected' : '' ?>>Apparel</option>
                <option value="Manga"   <?= $kategori === 'Manga'   ? 'selected' : '' ?>>Manga</option>
            </select>

            <button type="submit" class="btn btn-primary" id="search-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px;" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                Cari
            </button>

            <?php if ($keyword !== '' || $kategori !== ''): ?>
            <a href="produk.php" class="btn btn-ghost" id="clear-filter-btn">
                ✕ Reset
            </a>
            <?php endif; ?>

        </form>
    </div>
</div>

<!-- ================================================================
     PRODUCT LISTING
     ================================================================ -->
<main class="main-content">
    <div class="container">

        <!-- Page heading -->
        <h1 class="page-heading">
            <?php if ($kategori !== ''): ?>
                <?= htmlspecialchars($kategori) ?>
            <?php elseif ($keyword !== ''): ?>
                Hasil Pencarian: "<?= htmlspecialchars($keyword) ?>"
            <?php else: ?>
                Semua Produk
            <?php endif; ?>
        </h1>

        <!-- Result count -->
        <p class="result-count">
            <?= $total ?> produk ditemukan
        </p>

        <!-- Product grid -->
        <?php if ($total > 0): ?>
        <div class="product-grid" id="produk-grid">

            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <article class="product-card" id="produk-card-<?= $row['id'] ?>">

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

                <!-- Info -->
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
                            id="detail-link-<?= $row['id'] ?>"
                        >
                            Lihat Detail
                        </a>
                        <button
                            class="btn btn-outline btn-sm"
                            title="Tambah ke Wishlist"
                            id="wish-<?= $row['id'] ?>"
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

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            <h3>Tidak Ada Produk</h3>
            <p>
                <?php if ($keyword !== '' || $kategori !== ''): ?>
                    Coba kata kunci lain atau <a href="produk.php" style="color:var(--accent); font-weight:600;">lihat semua produk</a>.
                <?php else: ?>
                    Belum ada produk. Silakan tambahkan melalui panel admin.
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php include "includes/footer.php"; ?>