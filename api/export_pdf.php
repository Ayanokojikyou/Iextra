<?php
ob_start();

include 'koneksi.php';

define('FPDF_FONTPATH', __DIR__ . '/fpdf186/font/');
require __DIR__ . '/fpdf186/fpdf.php';

$logo = __DIR__ . '/../assets/lextra.png';

$jaksa = $_POST['jaksa_peneliti'];

// Query
if ($jaksa == "semua") {
  $query = mysqli_query($koneksi, "SELECT * FROM berkas ORDER BY jaksa_peneliti ASC");
} else {
  $query = mysqli_query($koneksi, "SELECT * FROM berkas WHERE jaksa_peneliti='$jaksa'");
}

// PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// LOGO (hindari warning)
if (file_exists($logo)) {
  $pdf->Image($logo, 15, 10, 25, 25);
}

// Header
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, 'LAW TIME TRACKER FOR KEJAKSAAN (LEXTRA)', 0, 1, 'C');
$pdf->Ln(3);

// ... lanjutkan semua isi PDF sama seperti sebelumnya ...

// FOOTER
$pdf->Ln(8);
$pdf->SetFont('Helvetica', 'I', 9);
$pdf->Cell(0, 8, 'Dicetak otomatis oleh sistem LEXTRA pada tanggal: ' . date('d-m-Y H:i'), 0, 1, 'R');

// Tutup koneksi sebelum kirim PDF
mysqli_close($koneksi);

// === FIX VERCEL ===
ob_end_clean();
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=Rekap_Berkas_Lextra.pdf");

$pdf->Output('I');
exit;
