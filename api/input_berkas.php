<?php
include 'koneksi.php';

// proses simpan data
if (isset($_POST['simpan'])) {
  $no_berkas = $_POST['no_berkas'];
  $nama_berkas = $_POST['nama_berkas'];
  $pasal = $_POST['pasal'];
  $nama_penyidik = $_POST['nama_penyidik'];
  $tanggal_input = $_POST['tanggal_input'];
  $status = $_POST['status'];
  $jaksa_peneliti = $_POST['jaksa_peneliti'];

  // hitung notifikasi otomatis (7 & 14 hari)
  $tgl_notif_7 = date('Y-m-d', strtotime($tanggal_input . ' +7 days'));
  $tgl_notif_14 = date('Y-m-d', strtotime($tanggal_input . ' +14 days'));

  $query = mysqli_query($koneksi, "INSERT INTO berkas 
    (no_berkas, nama_berkas, pasal, nama_penyidik, tanggal_input, tgl_notif_7, tgl_notif_14, status, jaksa_peneliti)
    VALUES ('$no_berkas', '$nama_berkas', '$pasal', '$nama_penyidik', '$tanggal_input', '$tgl_notif_7', '$tgl_notif_14', '$status', '$jaksa_peneliti')");

  if ($query) {
    echo "<script>alert('Data berhasil disimpan!'); window.location='rekapitulasi.php';</script>";
  } else {
    echo "<script>alert('Gagal menyimpan data!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LEXTRA | Input Data Berkas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 230px;
      height: 100vh;
      background-color: #6b0f1a;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
    }

    .sidebar img {
      width: 100px;
      margin-bottom: 20px;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 5px;
    }

    .sidebar p {
      font-size: 10px;
      margin-bottom: 25px;
    }

    .sidebar a {
      display: block;
      width: 90%;
      padding: 10px 15px;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      margin: 5px 0;
      font-size: 15px;
    }

    .sidebar a.active,
    .sidebar a:hover {
      background-color: #500c14;
    }

    .main {
      margin-left: 230px;
      padding: 30px;
    }

    .header-top {
      text-align: center;
      margin-bottom: 15px;
    }

    .header-top h1 {
      color: #681212;
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 0;
    }

    .header-top small {
      font-size: 14px;
      color: #681212;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 900px;
      margin: 0 auto;
    }

    /* h2 {
      color: #000;
      font-weight: 600;
      font-size: 22px;
      margin-bottom: 25px;
    } */

    .btn-success {
      background-color: #681212;
      border: none;
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 8px;
      color: #fff;
    }

    /* .btn-success:hover {
      background-color: #157347;
    } */

    small {
      font-size: 13px;
      color: #6c757d;
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <img src="assets/lextra.png" alt="Logo">
    <h2>LEXTRA</h2>
    <p>LAW TIME TRACKER FOR KEJAKSAAN</p>
    <a href="index.php">🏠 Home</a>
    <a href="input_berkas.php">📂 Input Berkas</a>
    <a href="rekapitulasi.php">📊 Monitoring</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header-top">
      <h1>LEXTRA</h1>
      <small>Law Time Tracker for Kejaksaan</small>
    </div>

    <div class="form-container">
      <h2>Input Data Berkas</h2>
      <form method="POST">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nomor Perkara</label>
            <input type="text" name="no_berkas" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Tersangka</label>
            <input type="text" name="nama_berkas" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Pasal</label>
            <input type="text" name="pasal" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Penyidik</label>
            <input type="text" name="nama_penyidik" class="form-control">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Penerimaan</label>
          <input type="date" name="tanggal_input" class="form-control" required>
          <small>Otomatis terhitung 7 dan 14 harinya untuk notifikasi.</small>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Status Awal</label>
            <select name="status" class="form-select">
              <option value="Berkas Aktif">Berkas Aktif</option>
              <option value="Mendekati Tenggat">Mendekati Tenggat</option>
              <option value="Tenggat">Tenggat</option>
              <option value="P-18">P-18</option>
              <option value="P-21">P-21</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Jaksa Peneliti</label>
            <select name="jaksa_peneliti" class="form-select" required>
              <option value="">Pilih nama</option>
              <?php
              $jaksa_query = mysqli_query($koneksi, "SELECT nama FROM users");
              while ($row = mysqli_fetch_assoc($jaksa_query)) {
                echo "<option value='{$row['nama']}'>{$row['nama']}</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <button type="submit" name="simpan" class="btn-success">Simpan Data</button>
      </form>
    </div>
  </div>

</body>

</html>