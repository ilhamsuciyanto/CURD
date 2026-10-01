<?php
session_start();

// Already logged in → redirect to admin
if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit;
}

$base_path  = '../';
$page_title = 'Login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login admin AniShop — Masuk ke panel manajemen.">
    <title>Login Admin — AniShop</title>
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

    <!-- Login box -->
    <div class="login-box">

        <h1 class="login-title">Log in</h1>

        <!-- Error message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" role="alert" id="login-error-msg">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Success message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert" id="login-success-msg">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Login form -->
        <form action="proses_login.php" method="POST" id="login-form" novalidate>

            <div class="form-group">
                <label class="form-label" for="login-username">Username</label>
                <input
                    type="text"
                    name="username"
                    id="login-username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="login-password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="login-password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                >
                <div class="form-hint">6 hingga 32 karakter (A-Z, a-z, 0-9)</div>
            </div>

            <button
                type="submit"
                class="btn btn-primary btn-full btn-lg"
                id="login-submit-btn"
                style="margin-top:8px;"
            >
                Log in
            </button>

        </form>

        <!-- Forgot password -->
        <div class="forgot-link" style="margin-top:16px;">
            <a href="#" id="forgot-password-link">Lupa password?</a>
        </div>

        <hr class="login-divider">

        <!-- Register CTA -->
        <div class="login-alt">
            Baru di AniShop?
            <a href="daftar.php" id="register-link">Daftar sekarang</a>
        </div>

        <!-- Back to site -->
        <div style="text-align:center; margin-top:16px;">
            <a href="../index.php" class="btn btn-ghost btn-sm" id="back-to-home-btn">
                ← Kembali ke Toko
            </a>
        </div>

    </div>

</div>

</body>
</html>