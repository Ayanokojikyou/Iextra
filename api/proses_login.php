<?php
include 'koneksi.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    header("Location: index.php");
} else {
    echo "<script>alert('Username atau password salah'); window.location='login.php';</script>";
}

mysqli_close($koneksi);
