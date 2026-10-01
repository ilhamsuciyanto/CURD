<?php
session_start();
require_once '../includes/auth.php';

// Helper: temukan file banner apapun ekstensinya
function findBanner(string $slot): ?string {
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        $path = '../uploads/banner/' . $slot . '.' . $ext;
        if (file_exists($path)) {
            return 'uploads/banner/' . $slot . '.' . $ext; // path untuk img src (relatif dari root)
        }
    }
    return null;
}

$banners = [
    'banner-main' => [
        'label'       => 'Banner Utama',
        'desc'        => 'Banner besar di sisi kiri halaman utama.',
        'recommended' => '800 × 350 px',
        'icon'        => '🖼️',
        'aspect'      => '800/350',
    ],
    'promo-1' => [
        'label'       => 'Banner Promo 1',
        'desc'        => 'Kotak promo pertama (atas) di sisi kanan.',
        'recommended' => '248 × 110 px',
        'icon'        => '📌',
        'aspect'      => '248/110',
    ],
    'promo-2' => [
        'label'       => 'Banner Promo 2',
        'desc'        => 'Kotak promo kedua (tengah) di sisi kanan.',
        'recommended' => '248 × 110 px',
        'icon'        => '📌',
        'aspect'      => '248/110',
    ],
    'promo-3' => [
        'label'       => 'Banner Promo 3',
        'desc'        => 'Kotak promo ketiga (bawah) di sisi kanan.',
        'recommended' => '248 × 110 px',
        'icon'        => '📌',
        'aspect'      => '248/110',
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Banner — AniShop Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* ── Banner card ── */
        .banner-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .banner-card-header {
            background: var(--bg-alt);
            border-bottom: 1px solid var(--border);
            padding: var(--s2) var(--s3);
            display: flex;
            align-items: center;
            gap: var(--s2);
        }
        .banner-card-icon {
            font-size: 20px;
            line-height: 1;
        }
        .banner-card-title  { font-size: var(--text-md); font-weight: 700; }
        .banner-card-desc   { font-size: var(--text-sm); color: var(--text-muted); margin-top: 1px; }
        .banner-card-body   { padding: var(--s3); flex: 1; display: flex; flex-direction: column; gap: var(--s2); }

        /* Preview area */
        .banner-preview {
            width: 100%;
            background: repeating-linear-gradient(
                45deg,
                #F5F5F5,
                #F5F5F5 10px,
                #EEEEEE 10px,
                #EEEEEE 20px
            );
            border: 1.5px dashed var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .banner-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .banner-preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: var(--text-light);
            padding: var(--s3);
            text-align: center;
        }
        .banner-preview-empty .ep-icon { font-size: 36px; line-height: 1; }
        .banner-preview-empty .ep-text { font-size: var(--text-sm); font-weight: 600; }
        .banner-preview-empty .ep-size { font-size: 12px; color: var(--border-dark); }

        /* Badge on preview */
        .banner-exists-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(46,125,50,0.9);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 99px;
        }

        /* Upload area */
        .banner-upload-zone {
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            padding: var(--s2) var(--s3);
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            background: var(--bg-alt);
            position: relative;
            overflow: hidden;
        }
        .banner-upload-zone:hover  { border-color: var(--accent); background: #FFF3EE; }
        .banner-upload-zone.active { border-color: var(--accent); background: #FFF3EE; }
        .banner-upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        /* New preview overlay */
        .new-preview-wrap { display: none; position: relative; }
        .new-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--radius);
            border: 2px solid var(--accent);
        }
        .new-preview-label {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(232,98,26,0.9);
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 8px;
            text-align: center;
        }

        /* Grid layout */
        .banner-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--s4);
            max-width: 1000px;
        }
        .promo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--s3);
        }
        @media (max-width: 700px) {
            .promo-grid { grid-template-columns: 1fr; }
        }
    </style>
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
        <a href="tambah.php"   class="admin-nav-link">+ Tambah</a>
        <a href="banner.php"   class="admin-nav-link active">Banner</a>
        <a href="../index.php" class="admin-nav-link">← Lihat Website</a>
        <a href="logout.php"   class="admin-nav-link danger" onclick="return confirm('Apakah kamu yakin mau logout?');">Logout</a>
    </nav>
</header>

<div class="container" style="padding-top:var(--s5);padding-bottom:var(--s8);">

    <!-- Page header -->
    <div style="margin-bottom:var(--s4);">
        <h1 style="font-size:var(--text-2xl);font-weight:700;">Kelola Banner</h1>
        <p style="color:var(--text-muted);font-size:var(--text-lg);margin-top:4px;">
            Upload gambar untuk banner utama dan 3 kotak promo di halaman beranda.
        </p>
    </div>

    <!-- Flash -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:var(--s3);max-width:1000px;">
            ✅ <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:var(--s3);max-width:1000px;">
            ⚠️ <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Layout diagram hint -->
    <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-lg);padding:var(--s3) var(--s4);margin-bottom:var(--s4);max-width:1000px;display:flex;align-items:center;gap:var(--s3);">
        <div style="flex-shrink:0;">
            <!-- Mini layout diagram -->
            <div style="display:flex;gap:6px;align-items:stretch;height:64px;">
                <div style="background:#1A237E;border-radius:4px;width:140px;display:flex;align-items:center;justify-content:center;color:white;font-size:10px;font-weight:700;">Banner Utama</div>
                <div style="display:flex;flex-direction:column;gap:4px;">
                    <div style="background:#E8621A;border-radius:3px;width:56px;flex:1;display:flex;align-items:center;justify-content:center;color:white;font-size:8px;font-weight:700;">Promo 1</div>
                    <div style="background:#E8621A;border-radius:3px;width:56px;flex:1;display:flex;align-items:center;justify-content:center;color:white;font-size:8px;font-weight:700;">Promo 2</div>
                    <div style="background:#E8621A;border-radius:3px;width:56px;flex:1;display:flex;align-items:center;justify-content:center;color:white;font-size:8px;font-weight:700;">Promo 3</div>
                </div>
            </div>
        </div>
        <div>
            <div style="font-weight:700;font-size:var(--text-base);margin-bottom:4px;">Tata Letak Banner di Beranda</div>
            <div style="font-size:var(--text-sm);color:var(--text-muted);line-height:1.6;">
                Banner Utama tampil besar di sisi kiri. Tiga kotak Promo tampil di sisi kanan sebagai highlight produk/promo.
                Format yang didukung: <strong>JPG, PNG, WebP</strong> · Maks. <strong>5 MB</strong> per file.
            </div>
        </div>
    </div>

    <!-- ═══ BANNER UTAMA ═══ -->
    <div style="margin-bottom:var(--s2);">
        <h2 style="font-size:var(--text-lg);font-weight:700;margin-bottom:var(--s2);">Banner Utama</h2>
    </div>

    <?php
    $slotMain   = 'banner-main';
    $infoMain   = $banners[$slotMain];
    $currentMain = findBanner($slotMain);
    ?>
    <div class="banner-card" style="max-width:1000px;margin-bottom:var(--s5);" id="card-<?= $slotMain ?>">
        <div class="banner-card-header">
            <span class="banner-card-icon"><?= $infoMain['icon'] ?></span>
            <div>
                <div class="banner-card-title"><?= $infoMain['label'] ?></div>
                <div class="banner-card-desc"><?= $infoMain['desc'] ?> Ukuran ideal: <?= $infoMain['recommended'] ?></div>
            </div>
        </div>
        <div class="banner-card-body">

            <!-- Preview area -->
            <div class="banner-preview" style="aspect-ratio:<?= $infoMain['aspect'] ?>;" id="preview-<?= $slotMain ?>">
                <?php if ($currentMain): ?>
                    <img src="../<?= htmlspecialchars($currentMain) ?>" alt="Banner Utama">
                    <div class="banner-exists-badge">✓ Terpasang</div>
                <?php else: ?>
                    <div class="banner-preview-empty">
                        <span class="ep-icon">🖼️</span>
                        <span class="ep-text">Belum ada banner</span>
                        <span class="ep-size"><?= $infoMain['recommended'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- New preview (muncul saat file dipilih) -->
            <div class="new-preview-wrap" id="newprev-<?= $slotMain ?>" style="aspect-ratio:<?= $infoMain['aspect'] ?>;">
                <img src="" alt="Preview baru">
                <div class="new-preview-label">🔄 Foto baru — belum disimpan, klik "Pasang" untuk upload</div>
            </div>

            <!-- Upload form -->
            <form action="proses_banner.php" method="POST" enctype="multipart/form-data"
                  id="form-<?= $slotMain ?>" style="display:flex;gap:var(--s2);align-items:center;flex-wrap:wrap;">
                <input type="hidden" name="slot" value="<?= $slotMain ?>">

                <div class="banner-upload-zone" style="flex:1;min-width:200px;"
                     onclick="document.getElementById('file-<?= $slotMain ?>').click()">
                    <input type="file" name="gambar" id="file-<?= $slotMain ?>"
                           accept=".jpg,.jpeg,.png,.webp"
                           onclick="event.stopPropagation()"
                           onchange="previewBanner(this, '<?= $slotMain ?>')">
                    <span style="font-size:18px;">📂</span>
                    <span style="font-size:var(--text-sm);font-weight:600;color:var(--text);margin:0 var(--s1);">
                        Klik untuk pilih gambar
                    </span>
                    <span style="font-size:var(--text-xs);color:var(--text-muted);" id="fname-<?= $slotMain ?>">
                        JPG, PNG, WebP · Maks. 5 MB
                    </span>
                </div>

                <button type="submit" class="btn btn-primary" id="submit-<?= $slotMain ?>" style="white-space:nowrap;">
                    🚀 Pasang Banner
                </button>

                <?php if ($currentMain): ?>
                <a href="hapus_banner.php?slot=<?= $slotMain ?>"
                   class="btn btn-sm"
                   id="hapus-<?= $slotMain ?>"
                   style="background:#FFEBEE;color:#C62828;border:1px solid #FFCDD2;white-space:nowrap;"
                   onclick="return confirm('Hapus banner utama?')">
                    🗑️ Hapus
                </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- ═══ BANNER PROMO ═══ -->
    <div style="margin-bottom:var(--s2);">
        <h2 style="font-size:var(--text-lg);font-weight:700;margin-bottom:var(--s2);">Banner Promo (3 kotak kanan)</h2>
    </div>

    <div class="promo-grid" style="max-width:1000px;">
    <?php
    $promoSlots = ['promo-1', 'promo-2', 'promo-3'];
    foreach ($promoSlots as $slot):
        $info    = $banners[$slot];
        $current = findBanner($slot);
    ?>
        <div class="banner-card" id="card-<?= $slot ?>">
            <div class="banner-card-header">
                <span class="banner-card-icon"><?= $info['icon'] ?></span>
                <div>
                    <div class="banner-card-title"><?= $info['label'] ?></div>
                    <div class="banner-card-desc">Ideal: <?= $info['recommended'] ?></div>
                </div>
            </div>
            <div class="banner-card-body">

                <!-- Preview -->
                <div class="banner-preview" style="aspect-ratio:<?= $info['aspect'] ?>;" id="preview-<?= $slot ?>">
                    <?php if ($current): ?>
                        <img src="../<?= htmlspecialchars($current) ?>" alt="<?= $info['label'] ?>">
                        <div class="banner-exists-badge">✓</div>
                    <?php else: ?>
                        <div class="banner-preview-empty">
                            <span class="ep-icon">📌</span>
                            <span class="ep-text">Kosong</span>
                            <span class="ep-size"><?= $info['recommended'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- New preview -->
                <div class="new-preview-wrap" id="newprev-<?= $slot ?>" style="aspect-ratio:<?= $info['aspect'] ?>;">
                    <img src="" alt="Preview">
                    <div class="new-preview-label">🔄 Belum disimpan</div>
                </div>

                <!-- Upload form -->
                <form action="proses_banner.php" method="POST" enctype="multipart/form-data" id="form-<?= $slot ?>">
                    <input type="hidden" name="slot" value="<?= $slot ?>">

                    <div class="banner-upload-zone" style="margin-bottom:var(--s2);"
                         onclick="document.getElementById('file-<?= $slot ?>').click()">
                        <input type="file" name="gambar" id="file-<?= $slot ?>"
                               accept=".jpg,.jpeg,.png,.webp"
                               onclick="event.stopPropagation()"
                               onchange="previewBanner(this, '<?= $slot ?>')">
                        <div>
                            <span style="font-size:16px;">📂</span>
                            <span style="font-size:var(--text-sm);font-weight:600;color:var(--text);margin-left:6px;">Pilih gambar</span>
                        </div>
                        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:4px;" id="fname-<?= $slot ?>">
                            JPG, PNG, WebP
                        </div>
                    </div>

                    <div style="display:flex;gap:6px;">
                        <button type="submit" class="btn btn-primary btn-sm" style="flex:1;" id="submit-<?= $slot ?>">
                            🚀 Pasang
                        </button>
                        <?php if ($current): ?>
                        <a href="hapus_banner.php?slot=<?= $slot ?>"
                           class="btn btn-sm"
                           id="hapus-<?= $slot ?>"
                           style="background:#FFEBEE;color:#C62828;border:1px solid #FFCDD2;"
                           onclick="return confirm('Hapus <?= $info['label'] ?>?')">
                            🗑️
                        </a>
                        <?php endif; ?>
                    </div>
                </form>

            </div>
        </div>
    <?php endforeach; ?>
    </div>

</div>

<script>
function previewBanner(input, slot) {
    const file = input.files[0];
    if (!file) return;

    // Tampilkan nama file di zone
    document.getElementById('fname-' + slot).textContent = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
        // Sembunyikan preview lama
        const oldPreview = document.getElementById('preview-' + slot);
        oldPreview.style.display = 'none';

        // Tampilkan preview baru
        const newWrap = document.getElementById('newprev-' + slot);
        newWrap.style.display = 'block';
        newWrap.querySelector('img').src = e.target.result;

        // Highlight upload zone
        input.closest('.banner-upload-zone').classList.add('active');
    };
    reader.readAsDataURL(file);
}
</script>

</body>
</html>
