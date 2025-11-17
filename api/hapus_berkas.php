<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = "DELETE FROM berkas WHERE id='$id'";

if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='rekapitulasi.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='rekapitulasi.php';</script>";
}
?>
