<?php
$host = "p47bof.h.filess.io";
$user = "iextra_treatedgas";
$pass = "7ca2cd44bd50079fb3fa99fe769b8062153bdce4";
$db   = "db_lextra";

$koneksi = mysqli_init();

mysqli_ssl_set($koneksi, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect(
    $koneksi,
    $host,
    $user,
    $pass,
    $db,
    3307,
    NULL,
    MYSQLI_CLIENT_SSL
)) {
    die("Koneksi GAGAL: " . mysqli_connect_error());
}

echo "Koneksi BERHASIL pakai SSL!";
