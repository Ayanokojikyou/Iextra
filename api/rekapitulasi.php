<?php
include 'koneksi.php';

// hapus data
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $hapus = mysqli_query($koneksi, "DELETE FROM berkas WHERE id='$id'");
  if ($hapus) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='rekapitulasi.php';</script>";
  } else {
    echo "<script>alert('Gagal menghapus data!');</script>";
  }
}

// filter status
$filter = "";
if (isset($_GET['status']) && $_GET['status'] != "") {
  $filter = "WHERE status='" . mysqli_real_escape_string($koneksi, $_GET['status']) . "'";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LEXTRA | Rekapitulasi Berkas</title>
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

    /* Main */
    .main {
      margin-left: 230px;
      padding: 30px;
    }

    .table-container {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .header-top {
      text-align: center;
      margin-bottom: 15px;
    }

    .header-top h1 {
      color: #6b0f1a;
      font-weight: 700;
      font-size: 24px;
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 20px 0;
    }

    .btn-filter,
    .btn-export {
      background-color: #6b0f1a;
      color: white;
      border: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-filter:hover,
    .btn-export:hover {
      background-color: #500c14;
      color: white;
    }

    thead th {
      background-color: #6b0f1a;
      color: white;
      text-align: center;
    }

    .aksi-btn {
      text-decoration: none;
      margin-right: 10px;
      font-weight: 500;
    }

    .edit-btn {
      color: #007bff;
    }

    .hapus-btn {
      color: #dc3545;
    }

    .edit-btn:hover,
    .hapus-btn:hover {
      text-decoration: underline;
    }

    .search-box {
      margin-bottom: 20px;
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
    <a href="rekapitulasi.php" class="active">📊 Rekapitulasi</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header-top">
      <h1>LEXTRA</h1>
      <small>Law Time Tracker for Kejaksaan</small>
    </div>

    <div class="table-container">
      <div class="header-row">
        <h2>Rekapitulasi Berkas</h2>
        <div>
          <button class="btn btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
          <button class="btn btn-export" data-bs-toggle="modal" data-bs-target="#exportModal">Export</button>
        </div>
      </div>

      <input type="text" id="search" class="form-control search-box" placeholder="🔍 Search">

      <table class="table table-bordered table-striped" id="berkasTable">
        <thead>
          <tr>
            <th>Nomor Perkara</th>
            <th>Nama Tersangka</th>
            <th>Pasal</th>
            <th>Nama Penyidik</th>
            <th>Status</th>
            <th>Jaksa Peneliti</th>
            <th style="width: 110px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query($koneksi, "SELECT * FROM berkas $filter ORDER BY id DESC");
          while ($data = mysqli_fetch_assoc($query)) {
            echo "<tr>
              <td>{$data['no_berkas']}</td>
              <td>{$data['nama_berkas']}</td>
              <td>{$data['pasal']}</td>
              <td>{$data['nama_penyidik']}</td>
              <td>{$data['status']}</td>
              <td>{$data['jaksa_peneliti']}</td>
              <td>
                <a href='edit_berkas.php?id={$data['id']}' class='aksi-btn edit-btn'>Edit</a>
                <a href='rekapitulasi.php?hapus={$data['id']}' class='aksi-btn hapus-btn' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
              </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Filter -->
  <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="GET" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Status Berkas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="Berkas Aktif">Berkas Aktif</option>
            <option value="Mendekati Tenggat">Mendekati Tenggat</option>
            <option value="Tenggat">Tenggat</option>
            <option value="P-18">P-18</option>
            <option value="P-21">P-21</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-filter">Terapkan Filter</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Export -->
  <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="export_pdf.php" method="POST" class="modal-content" target="_blank">
        <div class="modal-header">
          <h5 class="modal-title" id="exportModalLabel">Export Berdasarkan Jaksa Peneliti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <select name="jaksa_peneliti" class="form-select" required>
            <option value="semua">-- Semua Jaksa Peneliti --</option>
            <?php
            $jaksa_query = mysqli_query($koneksi, "SELECT DISTINCT jaksa_peneliti FROM berkas ORDER BY jaksa_peneliti ASC");
            while ($j = mysqli_fetch_assoc($jaksa_query)) {
              echo "<option value='{$j['jaksa_peneliti']}'>{$j['jaksa_peneliti']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-export">Export PDF</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fitur pencarian sederhana
    document.getElementById('search').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll('#berkasTable tbody tr');
      rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  </script>

</body>

</html>

<?php mysqli_close($koneksi); ?>