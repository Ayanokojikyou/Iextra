<?php

include 'koneksi.php';

// Daftar Jaksa untuk dropdown
$daftar_jaksa = [
  "MARGARETHA HARTY PATURU, S.H., M.H.",
  "SISWANDI, S.H., M.H.",
  "YOGA PRADILA SANJAYA, S.H.,M.H.",
  "A THIRTA MASSAGUNI, S.H.",
  "AGUS SUSANDI, S.H., M.H.",
  "KOHARUDIN, S.H., M.H.",
  "ERLYSA SAID, S.H.",
  "AISYAH KENDEK, S.H.",
  "FITRIANI BAKRI, S.H.",
  "IRMAWATI, S.H."
];

// koneksi database
// $koneksi = mysqli_connect("localhost", "root", "", "db_lextra");
// if (mysqli_connect_errno()) {
//   echo "Koneksi database gagal: " . mysqli_connect_error();
//   exit;
// }

// ambil data berdasarkan ID
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = mysqli_query($koneksi, "SELECT * FROM berkas WHERE id='$id'");
  $data = mysqli_fetch_assoc($query);
  if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='rekapitulasi.php';</script>";
    exit;
  }
} else {
  echo "<script>alert('ID tidak valid!'); window.location='rekapitulasi.php';</script>";
  exit;
}

// update data ke database
if (isset($_POST['update'])) {
  $no_berkas = $_POST['no_berkas'];
  $nama_berkas = $_POST['nama_berkas'];
  $pasal = $_POST['pasal'];
  $nama_penyidik = $_POST['nama_penyidik'];
  $tanggal_input = $_POST['tanggal_input'];
  $status = $_POST['status'];
  $jaksa_peneliti = $_POST['jaksa_peneliti'];

  $update = mysqli_query($koneksi, "UPDATE berkas SET 
      no_berkas='$no_berkas',
      nama_berkas='$nama_berkas',
      pasal='$pasal',
      nama_penyidik='$nama_penyidik',
      tanggal_input='$tanggal_input',
      status='$status',
      jaksa_peneliti='$jaksa_peneliti'
      WHERE id='$id'");

  if ($update) {
    echo "<script>alert('Data berhasil diperbarui!'); window.location='rekapitulasi.php';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Data Berkas | LEXTRA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

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
      padding: 40px;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: auto;
    }

    /* h2 {
      text-align: center;
      color: #681212;
      font-weight: 700;
      margin-bottom: 25px;
    } */

    .btn-update {
      background-color: #198754;
      border: none;
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 8px;
      color: white;
    }

    .btn-update:hover {
      background-color: #157347;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
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
    <div class="form-container">
      <h2>Edit Data Berkas</h2>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nomor Perkara</label>
          <input type="text" name="no_berkas" class="form-control" value="<?= $data['no_berkas']; ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Tersangka</label>
          <input type="text" name="nama_berkas" class="form-control" value="<?= $data['nama_berkas']; ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Pasal</label>
          <input type="text" name="pasal" class="form-control" value="<?= $data['pasal']; ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Penyidik</label>
          <input type="text" name="nama_penyidik" class="form-control" value="<?= $data['nama_penyidik']; ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Penerimaan</label>
          <input type="date" name="tanggal_input" class="form-control" value="<?= $data['tanggal_input']; ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option <?= ($data['status'] == 'Berkas Aktif') ? 'selected' : ''; ?>>Berkas Aktif</option>
            <option <?= ($data['status'] == 'Mendekati Tenggat') ? 'selected' : ''; ?>>Mendekati Tenggat</option>
            <option <?= ($data['status'] == 'Tenggat') ? 'selected' : ''; ?>>Tenggat</option>
            <option <?= ($data['status'] == 'P-18') ? 'selected' : ''; ?>>P-18</option>
            <option <?= ($data['status'] == 'P-21') ? 'selected' : ''; ?>>P-21</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Jaksa Peneliti</label>
          <select name="jaksa_peneliti" class="form-select" required>
            <option value="">Pilih nama</option>
            <?php foreach ($daftar_jaksa as $jaksa) { ?>
              <option value="<?= $jaksa ?>" <?= ($data['jaksa_peneliti'] == $jaksa) ? 'selected' : '' ?>>
                <?= $jaksa ?>
              </option>
            <?php } ?>
          </select>
        </div>


        <button type="submit" name="update" class="btn-update">Update Data</button>
      </form>
    </div>
  </div>

</body>

</html>