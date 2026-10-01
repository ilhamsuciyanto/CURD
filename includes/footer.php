<?php
/**
 * includes/footer.php
 * Reusable site footer + closing HTML
 * 
 * Expects $base_path to be set by the including page.
 */
?>

<!-- ================================================================
     FOOTER
     ================================================================ -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <span class="logo-text" style="display:block; margin-bottom: 16px;">
                    <span class="ani">Ani</span><span class="shop">Shop</span>
                </span>
                <p>Toko merchandise anime terlengkap. Figure, goods, apparel, manga, dan lebih banyak — dikirim ke seluruh Indonesia.</p>
            </div>

            <!-- Kategori -->
            <div>
                <div class="footer-col-title">Kategori</div>
                <ul class="footer-links">
                    <li><a href="<?= $base_path ?>produk.php?kategori=Figure">Figure</a></li>
                    <li><a href="<?= $base_path ?>produk.php?kategori=Goods">Goods</a></li>
                    <li><a href="<?= $base_path ?>produk.php?kategori=Apparel">Apparel</a></li>
                    <li><a href="<?= $base_path ?>produk.php?kategori=Manga">Manga</a></li>
                    <li><a href="<?= $base_path ?>produk.php?sale=1">★ Sale</a></li>
                </ul>
            </div>

            <!-- Informasi -->
            <div>
                <div class="footer-col-title">Informasi</div>
                <ul class="footer-links">
                    <li><a href="<?= $base_path ?>tentang.php">Tentang Kami</a></li>
                    <li><a href="#">Cara Pemesanan</a></li>
                    <li><a href="#">Info Pengiriman</a></li>
                    <li><a href="#">Kebijakan Pengembalian</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div>
                <div class="footer-col-title">Bantuan</div>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                    <li><a href="#">Lacak Pesanan</a></li>
                    <li><a href="<?= $base_path ?>auth/login.php">Login Admin</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>© <?= date('Y') ?> AniShop. All rights reserved.</span>
            <span>Made with ♡ for anime fans</span>
        </div>
    </div>
</footer>

</body>
</html>
