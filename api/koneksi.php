<?php
$host = "p47bof.h.filess.io";
$user = "iextra_treatedgas"; // default XAMPP
$pass = "7ca2cd44bd50079fb3fa99fe769b8062153bdce4";     // kosongkan kalau belum diatur
$db   = "iextra_treatedgas";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
