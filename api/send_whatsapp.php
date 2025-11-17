<?php
function sendWhatsApp($no_wa, $pesan) {
    // 🔑 Token dari Fonnte (ganti dengan token kamu sendiri)
    $token = "irbUfccquGotFbdGFCj2"; 
    $url = "https://api.fonnte.com/send";

    // 📦 Data pesan
    $data = [
        'target' => $no_wa,
        'message' => $pesan,
    ];

    // 🔄 Kirim ke API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
            "Authorization: $token"
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    // 🕒 Buat log pengiriman (tanggal, nomor, dan isi pesan)
    $log_message = "[" . date('Y-m-d H:i:s') . "] " . 
                   "Kirim ke: $no_wa | Pesan: " . str_replace("\n", " ", $pesan) . "\n";

    // Simpan ke file log_wa.txt
    file_put_contents("log_wa.txt", $log_message, FILE_APPEND);

    // Kembalikan hasil tanpa menampilkan ke layar
    return $response;
}
?>
