<?php
// ================================
// 🔔 AUTO NOTIF WHATSAPP LEXTRA
// Kirim otomatis ke admin (tepat hari ke-7 & ke-14 dari tanggal_input)
// ================================

include 'koneksi.php';
include 'send_whatsapp.php';

// Nomor WhatsApp admin
$no_admin = "6287751287782"; // format internasional tanpa '+'

// Tanggal hari ini
$today = date('Y-m-d');

// Ambil data dari tabel berkas
$query = mysqli_query($koneksi, "
    SELECT 
        id,
        no_berkas,
        nama_berkas,
        pasal,
        nama_penyidik,
        jaksa_peneliti,
        status,
        tanggal_input
    FROM berkas
    WHERE status = 'Berkas Aktif'   -- hanya berkas aktif
");

if (mysqli_num_rows($query) > 0) {
    $ada_notif = false;

    while ($row = mysqli_fetch_assoc($query)) {
        // Lewati jika tanggal_input kosong
        if (empty($row['tanggal_input'])) continue;

        $tgl_input = date('Y-m-d', strtotime($row['tanggal_input']));
        $tgl_notif_7 = date('Y-m-d', strtotime($tgl_input . ' +7 days'));
        $tgl_notif_14 = date('Y-m-d', strtotime($tgl_input . ' +14 days'));

        // Cek apakah hari ini tepat tgl ke-7 atau ke-14
        if ($today == $tgl_notif_7 || $today == $tgl_notif_14) {
            $ada_notif = true;

            $status_notif = ($today == $tgl_notif_14)
                ? "Telah Mencapai Tenggat"
                : "Mendekati Tenggat";

            // Format pesan WhatsApp
            $pesan = "⚖️ *LEXTRA - Notifikasi Berkas*\n\n" .
                     "📁 *Nomor Berkas* : {$row['no_berkas']}\n" .
                     "👤 *Nama Tersangka* : {$row['nama_berkas']}\n" .
                     "📜 *Pasal* : {$row['pasal']}\n" .
                     "👮 *Nama Penyidik* : {$row['nama_penyidik']}\n" .
                     "⚖️ *Jaksa Peneliti* : {$row['jaksa_peneliti']}\n" .
                     "📅 *Tanggal* : {$today}\n" .
                     "📌 *Status* : {$status_notif}\n\n" .
                     "Segera tindak lanjuti berkas sesuai jadwal.";

            // Kirim pesan ke admin
            sendWhatsApp($no_admin, $pesan);

            // Simpan log
            $log = "[" . date('Y-m-d H:i:s') . "] Notif terkirim: {$row['no_berkas']} ($status_notif)\n";
            file_put_contents("log_wa.txt", $log, FILE_APPEND);
        }
    }

    if ($ada_notif) {
        echo "✅ Notifikasi dikirim ke admin ($no_admin) pada $today.";
    } else {
        echo "ℹ️ Tidak ada berkas yang jatuh tempo hari ini ($today).";
    }
} else {
    echo "⚠️ Tidak ada data berkas di database.";
}
?>
