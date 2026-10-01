<?php
session_start();

// Sudah login → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar akun AniShop — Buat akun baru gratis.">
    <title>Daftar Akun — AniShop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="login-page">

    <!-- Logo -->
    <div class="login-logo">
        <a href="../index.php" style="text-decoration:none;" aria-label="Kembali ke AniShop">
            <div class="logo-text" style="font-size:40px; line-height:1;">
                <span class="ani">Ani</span><span class="shop">Shop</span>
            </div>
        </a>
    </div>

    <!-- Register box -->
    <div class="login-box">

        <h1 class="login-title">Buat Akun Baru</h1>

        <!-- Error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" role="alert" id="register-error-msg">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Success -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert" id="register-success-msg">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Form Daftar -->
        <form action="proses_daftar.php" method="POST" id="register-form" novalidate>

            <div class="form-group">
                <label class="form-label" for="register-nama">Nama Lengkap</label>
                <input
                    type="text"
                    name="nama"
                    id="register-nama"
                    class="form-control"
                    placeholder="Masukkan nama lengkap"
                    value="<?= htmlspecialchars($_SESSION['form_nama'] ?? '') ?>"
                    required
                    autocomplete="name"
                    maxlength="100"
                >
                <?php unset($_SESSION['form_nama']); ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="register-email">Alamat E-mail</label>
                <input
                    type="email"
                    name="email"
                    id="register-email"
                    class="form-control"
                    placeholder="contoh@email.com"
                    value="<?= htmlspecialchars($_SESSION['form_email'] ?? '') ?>"
                    required
                    autocomplete="email"
                    maxlength="100"
                >
                <?php unset($_SESSION['form_email']); ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="register-password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="register-password"
                    class="form-control"
                    placeholder="Buat password"
                    required
                    autocomplete="new-password"
                    minlength="6"
                    maxlength="32"
                >
                <div class="form-hint">6 hingga 32 karakter</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="register-konfirmasi">Konfirmasi Password</label>
                <input
                    type="password"
                    name="konfirmasi"
                    id="register-konfirmasi"
                    class="form-control"
                    placeholder="Ulangi password"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button
                type="submit"
                class="btn btn-primary btn-full btn-lg"
                id="register-submit-btn"
                style="margin-top:8px;"
            >
                Buat Akun
            </button>

        </form>

        <hr class="login-divider">

        <!-- Link ke login -->
        <div class="login-alt">
            Sudah punya akun?
            <a href="login.php" id="go-to-login-link">Log in sekarang</a>
        </div>

        <!-- Kembali ke toko -->
        <div style="text-align:center; margin-top:16px;">
            <a href="../index.php" class="btn btn-ghost btn-sm" id="back-to-store-btn">
                ← Kembali ke Toko
            </a>
        </div>

    </div>

</div>

</body>
</html>
