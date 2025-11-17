<?php

// Cegah pembuatan koneksi lebih dari sekali (per request)
if (!isset($koneksi) || !$koneksi instanceof mysqli) {

    $host = "p47bof.h.filess.io";
    $user = "iextra_treatedgas";
    $pass = "7ca2cd44bd50079fb3fa99fe769b8062153bdce4";
    $db   = "iextra_treatedgas";

    // Inisialisasi koneksi
    $koneksi = mysqli_init();

    // SSL (wajib untuk Filess.io)
    mysqli_ssl_set($koneksi, NULL, NULL, NULL, NULL, NULL);

    // Coba konek
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
        error_log("MYSQL ERROR: " . mysqli_connect_error());
        die("Database connection failed.");
    }

    // Auto-close koneksi ketika script selesai (termasuk saat error)
    register_shutdown_function(function () use ($koneksi) {
        if ($koneksi && $koneksi instanceof mysqli) {
            mysqli_close($koneksi);
        }
    });
}
