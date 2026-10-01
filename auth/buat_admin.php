<?php

require_once "../config/database.php";

$nama = "Administrator";
$username = "admin";
$password = "admin123";

$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$query = mysqli_query(
    $conn,
    "INSERT INTO admin
    (nama, username, password)
    VALUES
    ('$nama', '$username', '$password_hash')"
);

if ($query) {

    echo "Admin berhasil dibuat.";

} else {

    echo "Gagal membuat admin.";
}