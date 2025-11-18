<?php
ob_start(); // WAJIB PALING ATAS
ini_set('output_buffering', 'On');
ini_set('zlib.output_compression', 0);

include 'koneksi.php';

define('FPDF_FONTPATH', __DIR__ . '/fpdf186/font/');
require __DIR__ . '/fpdf186/fpdf.php';

$logo = __DIR__ . '/../assets/lextra.png';

function RowEqualHeight($pdf, $widths, $data, $lineHeight = 5)
{
  // 1. Hitung jumlah baris terbesar
  $maxLines = 0;
  foreach ($data as $i => $txt) {
    $textWidth = $pdf->GetStringWidth($txt);
    $lines = ceil($textWidth / $widths[$i]);
    if ($lines > $maxLines) {
      $maxLines = $lines;
    }
  }

  $rowHeight = $maxLines * $lineHeight;

  // 2. Simpan posisi Y saat awal baris
  $startX = $pdf->GetX();
  $startY = $pdf->GetY();

  // 3. Gambar cell kosong dengan border
  foreach ($widths as $i => $w) {
    $pdf->Rect($startX, $startY, $w, $rowHeight);
    $startX += $w;
  }

  // 4. Isi teks per kolom menggunakan MultiCell
  $pdf->SetXY($pdf->GetX(), $startY);

  foreach ($data as $i => $txt) {
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->MultiCell($widths[$i], $lineHeight, $txt, 0);

    // Kembalikan ke kolom berikutnya (sejajar atas)
    $pdf->SetXY($x + $widths[$i], $y);
  }

  // 5. Pindah ke baris selanjutnya
  $pdf->Ln($rowHeight);
}

$jaksa = $_POST['jaksa_peneliti'];

// AMBIL DATA SESUAI FILTER
if ($jaksa == "semua") {
  $query = mysqli_query($koneksi, "SELECT * FROM berkas ORDER BY jaksa_peneliti ASC");
} else {
  $query = mysqli_query($koneksi, "SELECT * FROM berkas WHERE jaksa_peneliti='$jaksa'");
}

// BUAT DOKUMEN PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetAutoPageBreak(true, 15);   // WAJIB UNTUK VERCEL
$pdf->AddPage();

// ===== HEADER LAPORAN =====

// LOAD LOGO (hindari warning jika file tidak ditemukan)
if (file_exists($logo)) {
  $pdf->Image($logo, 15, 10, 25, 25);
}

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'LAW TIME TRACKER FOR KEJAKSAAN (LEXTRA)', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'REKAPITULASI BERKAS PENELITIAN', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
if ($jaksa != "semua") {
  $pdf->Cell(0, 8, "Jaksa Peneliti: $jaksa", 0, 1, 'C');
} else {
  $pdf->Cell(0, 8, "Semua Jaksa Peneliti", 0, 1, 'C');
}
$pdf->Ln(5);

// Garis pembatas
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
$pdf->Ln(8);

// ===== HEADER TABEL =====
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'No Berkas', 1, 0, 'C', true);
$pdf->Cell(45, 10, 'Nama Tersangka', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Pasal', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Nama Penyidik', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Status', 1, 0, 'C', true);
$pdf->Cell(55, 10, 'Jaksa Peneliti', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Tanggal Input', 1, 1, 'C', true);

// ===== ISI TABEL =====
$widths = [10, 35, 45, 30, 40, 35, 55, 35];

$pdf->SetFont('Arial', '', 9);
$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
  // $pdf->Cell(10, 8, $no++, 1, 0, 'C');
  // $pdf->Cell(35, 8, $row['no_berkas'], 1, 0);
  // $pdf->Cell(45, 8, $row['nama_berkas'], 1, 0);
  // $pdf->Cell(30, 8, $row['pasal'], 1, 0);
  // $pdf->Cell(40, 8, $row['nama_penyidik'], 1, 0);
  // $pdf->Cell(35, 8, $row['status'], 1, 0);
  // $pdf->Cell(55, 8, $row['jaksa_peneliti'], 1, 0);
  // $pdf->Cell(35, 8, $row['tanggal_input'], 1, 1);
  RowEqualHeight($pdf, $widths, [
    $no++,
    $row['no_berkas'],
    $row['nama_berkas'],
    $row['pasal'],
    $row['nama_penyidik'],
    $row['status'],
    $row['jaksa_peneliti'],
    $row['tanggal_input']
  ]);
}

// ===== FOOTER LAPORAN =====
$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 8, 'Dicetak otomatis oleh sistem LEXTRA pada tanggal: ' . date('d-m-Y H:i'), 0, 1, 'R');

// ===== TUTUP KONEKSI =====
mysqli_close($koneksi);

// ===== FIX UNTUK VERCEL (WAJIB) =====
ob_end_clean(); // hilangkan output bocor dari PHP / warning / BOM
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=Rekap_Berkas_Lextra.pdf");

$pdf->Output('I');
exit;
