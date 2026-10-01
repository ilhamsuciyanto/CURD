<?php
session_start();
require_once '../includes/auth.php';
require_once '../config/database.php';

$kategori_list = [
    'Figur & Model'      => ['Figure' => 'Figure (Skala 1/7, 1/8, dll.)', 'Nendoroid' => 'Nendoroid', 'Figma' => 'Figma (Action Figure)', 'Scale Model' => 'Scale Model / Gunpla'],
    'Barang & Aksesoris' => ['Goods' => 'Goods (Gantungan, Badge, dll.)', 'Apparel' => 'Apparel (Kaos, Hoodie, dll.)', 'Stationery' => 'Stationery (Alat Tulis Anime)', 'Poster' => 'Poster & Tapestry'],
    'Media'              => ['Manga' => 'Manga', 'Light Novel' => 'Light Novel', 'Blu-ray / DVD' => 'Blu-ray / DVD', 'Soundtrack' => 'CD Soundtrack'],
    'Lainnya'            => ['Lainnya' => 'Lainnya'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk — AniShop Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="admin-page-bg">

<!-- Topbar -->
<header class="admin-topbar">
    <nav class="admin-nav">
        <div class="admin-brand">
            <span class="ani">Ani</span><span class="shop">Shop</span>
            <span style="font-size:11px;opacity:0.6;display:block;font-weight:500;line-height:1;">Admin Panel</span>
        </div>
        <a href="index.php"    class="admin-nav-link">Dashboard</a>
        <a href="produk.php"   class="admin-nav-link">Produk</a>
        <a href="tambah.php"   class="admin-nav-link active">+ Tambah</a>
        <a href="../index.php" class="admin-nav-link">← Lihat Website</a>
        <a href="logout.php"   class="admin-nav-link danger" onclick="return confirm('Apakah kamu yakin mau logout?');">Logout</a>
    </nav>
</header>

<div class="container" style="padding-top:var(--s5);padding-bottom:var(--s8);">

    <div style="margin-bottom:var(--s4);">
        <h1 style="font-size:var(--text-2xl);font-weight:700;">Tambah Produk Baru</h1>
        <p style="color:var(--text-muted);font-size:var(--text-lg);margin-top:4px;">
            Isi semua informasi produk di bawah ini, lalu klik <strong>Simpan Produk</strong>.
        </p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="max-width:1100px;margin-bottom:var(--s3);">
            ⚠️ <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="proses_produk.php" method="POST" enctype="multipart/form-data" id="form-tambah">
        <input type="hidden" name="aksi" value="tambah">

        <div class="admin-form-layout">

            <!-- ═══════ KOLOM KIRI ═══════ -->
            <div style="display:flex;flex-direction:column;gap:var(--s3);">

                <!-- Section 1: Informasi -->
                <div class="admin-form-section">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon">1</div>
                        <div class="admin-form-section-title">Informasi Produk</div>
                    </div>
                    <div class="admin-form-section-body">

                        <div class="form-group" style="margin:0;">
                            <label class="form-label" for="input-nama">Nama Produk</label>
                            <input type="text" name="nama" id="input-nama" class="form-control"
                                   placeholder="Contoh: Nendoroid Hatsune Miku #033"
                                   maxlength="100" required
                                   oninput="updatePreviewName(this.value)">
                        </div>

                        <div class="form-group" style="margin:0;">
                            <label class="form-label" for="input-kategori">Kategori</label>
                            <select name="kategori" id="input-kategori" class="form-control"
                                    required onchange="updatePreviewCat(this.value)" style="cursor:pointer;">
                                <option value="" disabled selected>— Pilih kategori —</option>
                                <?php foreach ($kategori_list as $group => $items): ?>
                                <optgroup label="<?= htmlspecialchars($group) ?>">
                                    <?php foreach ($items as $val => $label): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin:0;">
                            <label class="form-label" for="input-deskripsi">Deskripsi Produk</label>
                            <textarea name="deskripsi" id="input-deskripsi" class="form-control"
                                      rows="5" required
                                      placeholder="Jelaskan detail produk: seri anime, ukuran, material, isi dalam box, dll."></textarea>
                        </div>

                    </div>
                </div>

                <!-- Section 2: Harga & Stok -->
                <div class="admin-form-section">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon">2</div>
                        <div class="admin-form-section-title">Harga &amp; Stok</div>
                    </div>
                    <div class="admin-form-section-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--s3);">

                            <div class="form-group" style="margin:0;">
                                <label class="form-label" for="input-harga">Harga Jual</label>
                                <div class="admin-price-wrap">
                                    <span class="admin-price-prefix">Rp</span>
                                    <input type="number" name="harga" id="input-harga" class="form-control"
                                           placeholder="150000" min="0" required
                                           oninput="updatePreviewPrice(this.value)">
                                </div>
                                <div class="form-hint">Masukkan angka tanpa titik/koma</div>
                            </div>

                            <div class="form-group" style="margin:0;">
                                <label class="form-label" for="input-stok">Jumlah Stok</label>
                                <input type="number" name="stok" id="input-stok" class="form-control"
                                       placeholder="10" min="0" required>
                                <div class="form-hint">Unit yang tersedia untuk dijual</div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Section 3: Foto -->
                <div class="admin-form-section">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon orange">3</div>
                        <div class="admin-form-section-title">Foto Produk</div>
                    </div>
                    <div class="admin-form-section-body">

                        <!-- Foto Utama -->
                        <div>
                            <span class="admin-badge orange">Foto Utama (Asli)</span>
                            <div class="admin-upload-box" onclick="document.getElementById('gambar').click()">
                                <input type="file" name="gambar" id="gambar"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       onclick="event.stopPropagation()"
                                       onchange="previewFoto(this,'prev-asli','summary-img-el','summary-placeholder')">
                                <span class="admin-upload-icon">🖼️</span>
                                <div class="admin-upload-label">Klik untuk pilih foto asli produk</div>
                                <div class="admin-upload-hint">JPG, PNG, WebP · Maks. 2 MB</div>
                            </div>
                            <div class="admin-new-preview" id="prev-asli">
                                <img src="" alt="Preview foto utama">
                                <div class="admin-new-preview-label">✅ Foto utama terpilih — klik upload box untuk ganti</div>
                            </div>
                            <div class="admin-hint orange">
                                📌 <span>Foto <strong>asli/nyata</strong> produk yang kamu jual. Tampil sebagai gambar utama di halaman detail.</span>
                            </div>
                        </div>

                        <!-- Foto Sample -->
                        <div>
                            <span class="admin-badge">Foto Sample (Opsional)</span>
                            <div class="admin-upload-box" onclick="document.getElementById('gambar_sample').click()">
                                <input type="file" name="gambar_sample" id="gambar_sample"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       onclick="event.stopPropagation()"
                                       onchange="previewFoto(this,'prev-sample',null,null)">
                                <span class="admin-upload-icon">🔲</span>
                                <div class="admin-upload-label">Klik untuk pilih foto sample</div>
                                <div class="admin-upload-hint">JPG, PNG, WebP · Maks. 2 MB</div>
                            </div>
                            <div class="admin-new-preview" id="prev-sample">
                                <img src="" alt="Preview foto sample">
                                <div class="admin-new-preview-label">✅ Foto sample terpilih</div>
                            </div>
                            <div class="admin-hint">
                                ℹ️ <span>Foto <strong>katalog/preview</strong> resmi. Muncul saat efek SAMPLE hover di halaman toko.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tombol -->
                <div style="display:flex;gap:var(--s2);">
                    <button type="submit" class="btn btn-primary btn-lg" style="flex:1;" id="btn-simpan">
                        💾 Simpan Produk
                    </button>
                    <a href="produk.php" class="btn btn-ghost btn-lg" id="btn-batal">Batal</a>
                </div>

            </div>

            <!-- ═══════ KOLOM KANAN — Preview ═══════ -->
            <div>
                <div class="admin-summary-card">
                    <div class="admin-summary-header">👀 Preview Tampilan di Toko</div>
                    <div class="admin-summary-body">
                        <div class="admin-summary-img-wrap">
                            <div class="admin-summary-placeholder" id="summary-placeholder">
                                <span style="font-size:40px;">🖼️</span>
                                <span>Foto akan muncul di sini</span>
                            </div>
                            <img src="" alt="Preview" id="summary-img-el" style="display:none;width:100%;height:100%;object-fit:contain;">
                        </div>
                        <div class="admin-summary-cat" id="summary-cat" style="color:var(--text-light);font-style:italic;">Kategori belum dipilih</div>
                        <div class="admin-summary-name" id="summary-name" style="color:var(--text-light);font-style:italic;">Nama produk belum diisi</div>
                        <div class="admin-summary-price" id="summary-price" style="color:var(--text-light);">Rp —</div>
                        <hr style="border:none;border-top:1px solid var(--border);margin:var(--s2) 0;">
                        <p style="font-size:var(--text-sm);color:var(--text-muted);line-height:1.6;">
                            Preview ini menunjukkan bagaimana produk akan terlihat di halaman toko.
                        </p>
                    </div>
                </div>

                <!-- Panduan -->
                <div class="admin-form-section" style="margin-top:var(--s3);">
                    <div class="admin-form-section-header">
                        <div class="admin-form-section-icon" style="background:#388E3C;">?</div>
                        <div class="admin-form-section-title">Panduan Foto</div>
                    </div>
                    <div class="admin-form-section-body">
                        <div style="display:flex;gap:var(--s2);align-items:flex-start;">
                            <span style="font-size:20px;flex-shrink:0;">🖼️</span>
                            <div>
                                <div style="font-weight:700;font-size:var(--text-sm);margin-bottom:2px;">Foto Utama (Asli)</div>
                                <div style="font-size:var(--text-sm);color:var(--text-muted);">Foto produk nyata yang kamu jual. Tampil sebagai gambar utama.</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:var(--s2);align-items:flex-start;">
                            <span style="font-size:20px;flex-shrink:0;">🔲</span>
                            <div>
                                <div style="font-weight:700;font-size:var(--text-sm);margin-bottom:2px;">Foto Sample</div>
                                <div style="font-size:var(--text-sm);color:var(--text-muted);">Foto katalog resmi. Muncul saat cursor diarahkan ke produk (efek SAMPLE).</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:var(--s2);align-items:flex-start;">
                            <span style="font-size:20px;flex-shrink:0;">📐</span>
                            <div>
                                <div style="font-weight:700;font-size:var(--text-sm);margin-bottom:2px;">Ukuran Ideal</div>
                                <div style="font-size:var(--text-sm);color:var(--text-muted);">Rasio 3:4 (Portrait). Contoh: 600×800px. Maks. 2 MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function previewFoto(input, previewId, summaryImgId, placeholderId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const div = document.getElementById(previewId);
        div.style.display = 'block';
        div.querySelector('img').src = e.target.result;
        if (summaryImgId) {
            const si = document.getElementById(summaryImgId);
            si.src = e.target.result;
            si.style.display = 'block';
        }
        if (placeholderId) {
            document.getElementById(placeholderId).style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}
function updatePreviewName(v) {
    const el = document.getElementById('summary-name');
    el.textContent = v || 'Nama produk belum diisi';
    el.style.color = v ? 'var(--text)' : 'var(--text-light)';
    el.style.fontStyle = v ? 'normal' : 'italic';
}
function updatePreviewCat(v) {
    const el = document.getElementById('summary-cat');
    el.textContent = v || 'Kategori belum dipilih';
    el.style.color = v ? 'var(--text-muted)' : 'var(--text-light)';
    el.style.fontStyle = v ? 'normal' : 'italic';
}
function updatePreviewPrice(v) {
    const el = document.getElementById('summary-price');
    if (v && !isNaN(v) && v >= 0) {
        el.textContent = 'Rp ' + parseInt(v).toLocaleString('id-ID');
        el.style.color = 'var(--accent)';
    } else {
        el.textContent = 'Rp —';
        el.style.color = 'var(--text-light)';
    }
}
</script>

</body>
</html>