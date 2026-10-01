<?php

session_start();

require_once "../config/database.php";

$username = trim(
    $_POST['username'] ?? ''
);

$password = $_POST['password'] ?? '';

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($username == '' || $password == '') {

    $_SESSION['error'] =
        "Username dan password wajib diisi.";

    header("Location: login.php");

    exit;
}

/*
|--------------------------------------------------------------------------
| CARI USERNAME
|--------------------------------------------------------------------------
*/

$username_safe = mysqli_real_escape_string(
    $conn,
    $username
);

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM admin
     WHERE username = '$username_safe'
     LIMIT 1"
);

$admin = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| CEK USERNAME DAN PASSWORD
|--------------------------------------------------------------------------
*/

if (
    $admin &&
    password_verify(
        $password,
        $admin['password']
    )
) {

    /*
    |--------------------------------------------------------------------------
    | LOGIN BERHASIL
    |--------------------------------------------------------------------------
    */

    session_regenerate_id(true);

    $_SESSION['admin_id'] =
        $admin['id'];

    $_SESSION['admin_nama'] =
        $admin['nama'];

    $_SESSION['admin_username'] =
        $admin['username'];

    header("Location: ../admin/index.php");

    exit;

} else {

    /*
    |--------------------------------------------------------------------------
    | LOGIN GAGAL
    |--------------------------------------------------------------------------
    */

    $_SESSION['error'] =
        "Username atau password salah.";

    header("Location: login.php");

    exit;
}