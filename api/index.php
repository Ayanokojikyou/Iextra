<?php
session_start();
include 'koneksi.php';
include 'send_whatsapp.php'; // pastikan file ini ada di folder yang sama

// Nomor WhatsApp Admin (format internasional tanpa tanda +)
$no_admin = "6281229954672"; // GANTI dengan nomor admin kamu

// ====== FUNGSI HITUNG STATUS ======
function hitungStatus($koneksi, $status) {
  $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM berkas WHERE status='$status'");
  $data = mysqli_fetch_assoc($query);
  return $data['total'];
}

// ====== HITUNG SETIAP STATUS ======
$berkas_aktif = hitungStatus($koneksi, 'Berkas Aktif');
$mendekati_tenggat = hitungStatus($koneksi, 'Mendekati Tenggat');
$tenggat = hitungStatus($koneksi, 'Tenggat');
$p18 = hitungStatus($koneksi, 'P-18');
$p21 = hitungStatus($koneksi, 'P-21');

// ====== LOGIKA NOTIFIKASI OTOMATIS ======
$today = date('Y-m-d');
$notif = mysqli_query($koneksi, "
  SELECT id, no_berkas, nama_berkas, pasal, nama_penyidik, jaksa_peneliti, tanggal_input, status
  FROM berkas
  WHERE status = 'Berkas Aktif'
");

// ====== KIRIM WHATSAPP JIKA HARI KE-7 ATAU KE-14 ======
while ($row = mysqli_fetch_assoc($notif)) {
  if (empty($row['tanggal_input'])) continue;

  $tgl_input = date('Y-m-d', strtotime($row['tanggal_input']));
  $tgl_notif_7 = date('Y-m-d', strtotime($tgl_input . ' +7 days'));
  $tgl_notif_14 = date('Y-m-d', strtotime($tgl_input . ' +14 days'));

  // Hanya kirim di tanggal yang tepat
  if ($today == $tgl_notif_7 || $today == $tgl_notif_14) {
    $status_notif = ($today == $tgl_notif_14)
      ? "Telah Mencapai Tenggat"
      : "Mendekati Tenggat";

    // ✅ Format pesan sesuai contoh kamu
    $pesan = "⚖️ *LEXTRA - Notifikasi Berkas Otomatis*\n\n" .
             "📁 *Nomor Berkas* : {$row['no_berkas']}\n" .
             "👤 *Nama Tersangka* : {$row['nama_berkas']}\n" .
             "📜 *Pasal* : {$row['pasal']}\n" .
             "👮 *Nama Penyidik* : {$row['nama_penyidik']}\n" .
             "⚖️ *Jaksa Peneliti* : {$row['jaksa_peneliti']}\n" .
             "📅 *Tanggal* : {$today}\n" .
             "📌 *Status* : {$status_notif}\n\n" .
             "Pesan ini dikirim otomatis oleh sistem *LEXTRA*.\n\n" .
             "> Sent via fonnte.com";

    sendWhatsApp($no_admin, $pesan);
  }
}

// Reset pointer hasil query untuk ditampilkan di bagian bawah
mysqli_data_seek($notif, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | LEXTRA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { font-family: "Poppins", sans-serif; }
    body { margin: 0; background-color: #f5f5f5; }

    /* Sidebar */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 230px; height: 100vh;
      background-color: #6b0f1a; color: white;
      display: flex; flex-direction: column; align-items: center;
      padding-top: 20px;
    }
    .sidebar img { width: 100px; margin-bottom: 20px; }
    .sidebar h2 { font-size: 18px; margin-bottom: 5px; }
    .sidebar p { font-size: 10px; margin-bottom: 25px; }
    .sidebar a {
      display: block; width: 90%; padding: 10px 15px;
      color: white; text-decoration: none;
      border-radius: 8px; margin: 5px 0; font-size: 15px;
    }
    .sidebar a.active, .sidebar a:hover { background-color: #500c14; }

    /* Main */
    .main { margin-left: 230px; padding: 30px; }
    .header { text-align: center; margin-bottom: 25px; }
    .header h1 { color: #6b0f1a; font-weight: 700; }

    .card-summary {
      display: flex; flex-wrap: wrap; justify-content: space-between;
      gap: 10px; margin-bottom: 30px;
    }

    .card-box {
      flex: 1; min-width: 150px; text-align: center;
      padding: 20px; border-radius: 12px; color: white;
    }

    .bg-blue { background-color: #223e8a; }
    .bg-yellow { background-color: #f0b429; }
    .bg-red { background-color: #d9534f; }
    .bg-orange { background-color: #f77f00; }
    .bg-green { background-color: #4caf50; }

    .notif-box {
      background-color: white; padding: 15px;
      border-radius: 10px; box-shadow: 0 3px 5px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    .notif-item {
      display: flex; align-items: center; gap: 10px;
      background-color: #fff7e6; padding: 10px;
      border-radius: 8px; margin-bottom: 10px;
    }
    .notif-item.danger { background-color: #ffe6e6; }
    .dot { width: 15px; height: 15px; border-radius: 50%; }
    .dot.yellow { background-color: #f0b429; }
    .dot.red { background-color: #d9534f; }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <img src="assets/lextra.png" alt="Logo">
    <h2>LEXTRA</h2>
    <p>LAW TIME TRACKER FOR KEJAKSAAN</p>
    <a href="index.php" class="active">🏠 Home</a>
    <a href="input_berkas.php">📂 Input Berkas</a>
    <a href="rekapitulasi.php">📊 Rekapitulasi</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main">
    <div class="header">
      <h1>Dashboard Utama</h1>
      <p>Ringkasan Status Berkas</p>
    </div>

    <!-- CARD RINGKASAN -->
    <div class="card-summary">
      <div class="card-box bg-blue"><h2><?= $berkas_aktif ?></h2><p>Berkas Aktif</p></div>
      <div class="card-box bg-yellow"><h2><?= $mendekati_tenggat ?></h2><p>Mendekati Tenggat</p></div>
      <div class="card-box bg-red"><h2><?= $tenggat ?></h2><p>Tenggat</p></div>
      <div class="card-box bg-orange"><h2><?= $p18 ?></h2><p>P-18</p></div>
      <div class="card-box bg-green"><h2><?= $p21 ?></h2><p>P-21</p></div>
    </div>

    <!-- GRAFIK -->
    <div class="card p-4 shadow-sm">
      <h5 class="text-center mb-3">Rekap Mingguan</h5>
      <canvas id="rekapChart"></canvas>
    </div>

    <!-- NOTIFIKASI -->
    <div class="notif-box mt-4">
      <h5>Notifikasi</h5>
      <?php
      $ada_notif = false;
      mysqli_data_seek($notif, 0); // reset ulang pointer
      while ($row = mysqli_fetch_assoc($notif)) {
        $tgl_input = date('Y-m-d', strtotime($row['tanggal_input']));
        $tgl_notif_7 = date('Y-m-d', strtotime($tgl_input . ' +7 days'));
        $tgl_notif_14 = date('Y-m-d', strtotime($tgl_input . ' +14 days'));

        if ($today == $tgl_notif_7 || $today == $tgl_notif_14) {
          $ada_notif = true;
          $warna = ($today == $tgl_notif_14) ? 'danger' : '';
          $dot = ($today == $tgl_notif_14) ? 'red' : 'yellow';
          $pesan = ($today == $tgl_notif_14)
            ? "Berkas No. {$row['no_berkas']} telah mencapai tenggat"
            : "Berkas No. {$row['no_berkas']} telah mendekati tenggat";
          echo "
          <div class='notif-item $warna'>
            <div class='dot $dot'></div>
            <span>$pesan</span>
          </div>";
        }
      }
      if (!$ada_notif) {
        echo "<p class='text-muted'>Tidak ada notifikasi hari ini.</p>";
      }
      ?>
    </div>
  </div>

  <!-- CHART -->
  <script>
    const ctx = document.getElementById('rekapChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Berkas Aktif', 'Mendekati Tenggat', 'Tenggat', 'P-18', 'P-21'],
        datasets: [{
          label: 'Jumlah Berkas',
          data: [<?= $berkas_aktif ?>, <?= $mendekati_tenggat ?>, <?= $tenggat ?>, <?= $p18 ?>, <?= $p21 ?>],
          backgroundColor: ['#223e8a', '#f0b429', '#d9534f', '#f77f00', '#4caf50']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, precision: 0 }
          }
        }
      }
    });
  </script>

</body>
</html>
