<?php
session_start();
require_once '../includes/auth.php';
require_once '../config/database.php';

$keyword  = trim($_GET['keyword']  ?? '');
$kategori = trim($_GET['kategori'] ?? '');

$where = [];
if ($keyword !== '') {
    $kw = mysqli_real_escape_string($conn, $keyword);
    $where[] = "nama LIKE '%$kw%'";
}
if ($kategori !== '') {
    $kat = mysqli_real_escape_string($conn, $kategori);
    $where[] = "kategori = '$kat'";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Pagination
$per_page   = 10;
$page       = max(1, (int) ($_GET['page'] ?? 1));
$offset     = ($page - 1) * $per_page;

$total_data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk $where_sql")
)['total'];

$total_page = (int) ceil($total_data / $per_page);
$query      = mysqli_query($conn,
    "SELECT * FROM produk $where_sql ORDER BY id DESC LIMIT $per_page OFFSET $offset"
);

// Ambil semua kategori unik untuk filter
$kat_result  = mysqli_query($conn, "SELECT DISTINCT kategori FROM produk ORDER BY kategori ASC");
$kat_options = [];
while ($r = mysqli_fetch_assoc($kat_result)) {
    $kat_options[] = $r['kategori'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk — AniShop Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* ── Table ── */
        .admin-table-wrap {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: var(--text-sm);
        }
        .admin-table th {
            background: var(--bg-alt);
            border-bottom: 1px solid var(--border);
            padding: var(--s2) var(--s3);
            text-align: left;
            font-weight: 700;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }
        .admin-table td {
            padding: var(--s2) var(--s3);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: var(--bg-alt); }

        .prod-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            display: block;
        }
        .prod-thumb-placeholder {
            width: 56px;
            height: 56px;
            background: var(--bg-alt);
            border: 1.5px dashed var(--border);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--text-light);
        }
        .sample-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            margin-left: 4px;
            vertical-align: middle;
            title: 'Ada foto sample';
        }
        .stok-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
        }
        .stok-badge.ok      { background: #E8F5E9; color: #2E7D32; }
        .stok-badge.low     { background: #FFF3E0; color: #E65100; }
        .stok-badge.empty   { background: #FFEBEE; color: #C62828; }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: var(--s3) var(--s4);
            display: flex;
            align-items: center;
            gap: var(--s2);
            flex-wrap: wrap;
            margin-bottom: var(--s3);
        }

        /* ── Pagination ── */
        .admin-pagination {
            display: flex;
            align-items: center;
            gap: var(--s1);
            padding: var(--s3) var(--s4);
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .admin-pagination a, .admin-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            font-size: var(--text-sm);
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }
        .admin-pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .admin-pagination span.current { background: var(--accent); color: white; border-color: var(--accent); }
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
        <a href="produk.php"   class="admin-nav-link active">Produk</a>
        <a href="tambah.php"   class="admin-nav-link">+ Tambah</a>
        <a href="../index.php" class="admin-nav-link">← Lihat Website</a>
        <a href="logout.php"   class="admin-nav-link danger" onclick="return confirm('Apakah kamu yakin mau logout?');">Logout</a>
    </nav>
</header>

<div class="container" style="padding-top:var(--s5);padding-bottom:var(--s8);">

    <!-- Header row -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--s4);flex-wrap:wrap;gap:var(--s2);">
        <div>
            <h1 style="font-size:var(--text-2xl);font-weight:700;">Kelola Produk</h1>
            <p style="color:var(--text-muted);font-size:var(--text-lg);margin-top:4px;">
                Total <strong><?= $total_data ?></strong> produk
            </p>
        </div>
        <a href="tambah.php" class="btn btn-primary btn-lg" id="btn-tambah-produk">
            + Tambah Produk Baru
        </a>
    </div>

    <!-- Flash messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:var(--s3);">
            ✅ <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:var(--s3);">
            ⚠️ <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Filter bar -->
    <form method="GET" id="filter-form">
        <div class="filter-bar">
            <input
                type="search"
                name="keyword"
                class="form-control"
                id="filter-keyword"
                placeholder="Cari nama produk..."
                value="<?= htmlspecialchars($keyword) ?>"
                style="max-width:280px;height:40px;"
            >
            <select name="kategori" class="form-control" id="filter-kategori"
                    onchange="this.form.submit()" style="max-width:200px;height:40px;cursor:pointer;">
                <option value="">Semua Kategori</option>
                <?php foreach ($kat_options as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>"
                        <?= $kategori === $opt ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary" style="height:40px;" id="filter-submit-btn">
                Cari
            </button>
            <?php if ($keyword !== '' || $kategori !== ''): ?>
            <a href="produk.php" class="btn btn-ghost" style="height:40px;" id="filter-reset-btn">
                ✕ Reset
            </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Table -->
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th style="width:80px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = $offset + 1;
            if (mysqli_num_rows($query) === 0):
            ?>
            <tr>
                <td colspan="7" style="text-align:center;padding:var(--s6);color:var(--text-muted);">
                    <div style="font-size:40px;margin-bottom:var(--s2);">📦</div>
                    <?= ($keyword || $kategori)
                        ? 'Tidak ada produk yang cocok dengan filter.'
                        : 'Belum ada produk. Klik "+ Tambah Produk Baru" untuk memulai.' ?>
                </td>
            </tr>
            <?php else: while ($row = mysqli_fetch_assoc($query)): ?>
            <tr id="row-produk-<?= $row['id'] ?>">
                <td style="color:var(--text-muted);"><?= $no++ ?></td>

                <!-- Foto -->
                <td>
                    <?php if ($row['gambar']): ?>
                        <img class="prod-thumb"
                             src="../uploads/produk/<?= htmlspecialchars($row['gambar']) ?>"
                             alt="<?= htmlspecialchars($row['nama']) ?>">
                    <?php else: ?>
                        <div class="prod-thumb-placeholder">🖼️</div>
                    <?php endif; ?>
                </td>

                <!-- Nama -->
                <td>
                    <div style="font-weight:600;color:var(--text);">
                        <?= htmlspecialchars($row['nama']) ?>
                        <?php if (!empty($row['gambar_sample'])): ?>
                            <span class="sample-dot" title="Ada foto sample"></span>
                        <?php endif; ?>
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                        ID #<?= $row['id'] ?>
                    </div>
                </td>

                <!-- Kategori -->
                <td>
                    <span style="background:var(--bg-alt);padding:2px 8px;border-radius:99px;font-size:12px;font-weight:600;">
                        <?= htmlspecialchars($row['kategori']) ?>
                    </span>
                </td>

                <!-- Harga -->
                <td style="font-weight:600;color:var(--accent);">
                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                </td>

                <!-- Stok -->
                <td>
                    <?php if ($row['stok'] <= 0): ?>
                        <span class="stok-badge empty">Habis</span>
                    <?php elseif ($row['stok'] <= 5): ?>
                        <span class="stok-badge low"><?= $row['stok'] ?> tersisa</span>
                    <?php else: ?>
                        <span class="stok-badge ok"><?= $row['stok'] ?></span>
                    <?php endif; ?>
                </td>

                <!-- Aksi -->
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="edit.php?id=<?= $row['id'] ?>"
                           class="btn btn-outline btn-sm"
                           id="edit-btn-<?= $row['id'] ?>">
                            ✏️ Edit
                        </a>
                        <a href="hapus.php?id=<?= $row['id'] ?>"
                           class="btn btn-sm"
                           style="background:#FFEBEE;color:#C62828;border:1px solid #FFCDD2;"
                           id="hapus-btn-<?= $row['id'] ?>"
                           onclick="return confirm('Hapus produk \'<?= htmlspecialchars(addslashes($row['nama'])) ?>\'?\n\nTindakan ini tidak dapat dibatalkan.')">
                            🗑️ Hapus
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_page > 1): ?>
        <div class="admin-pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&keyword=<?= urlencode($keyword) ?>&kategori=<?= urlencode($kategori) ?>">‹</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&kategori=<?= urlencode($kategori) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $total_page): ?>
                <a href="?page=<?= $page+1 ?>&keyword=<?= urlencode($keyword) ?>&kategori=<?= urlencode($kategori) ?>">›</a>
            <?php endif; ?>

            <span style="width:auto;border:none;font-size:var(--text-sm);color:var(--text-muted);padding:0 var(--s1);">
                Halaman <?= $page ?> dari <?= $total_page ?>
            </span>
        </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>