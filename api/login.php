<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$email' OR nama='$email'");
  $data = mysqli_fetch_assoc($query);

  if ($data) {
    if ($password == $data['password']) {
      $_SESSION['username'] = $data['username'];
      $_SESSION['nama'] = $data['nama'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Password salah!";
    }
  } else {
    $error = "Akun tidak ditemukan!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LEXTRA | Login</title>
  <style>
    * {
      font-family: "Poppins", sans-serif;
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #6b0f1a; /* warna maroon */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .logo {
      text-align: center;
      margin-bottom: 10px;
    }

    .logo img {
      width: 140px;
    }

    .title {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
    }

    .title h1 {
      font-size: 32px;
      font-weight: 700;
      margin: 10px 0 5px 0;
    }

    .title p {
      font-size: 14px;
      margin: 0;
      opacity: 0.9;
    }

    .login-container {
      text-align: center;
      width: 100%;
      max-width: 340px;
      background-color: white;
      padding: 25px 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    button {
      background-color: #6b0f1a;
      color: white;
      border: none;
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #500c14;
    }

    .error {
      color: red;
      font-size: 13px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <!-- Bagian Logo -->
  <div class="logo">
    <img src="assets/lextra.png" alt="Logo Kejaksaan">
  </div>

  <!-- Teks Judul -->
  <div class="title">
    <h1>LEXTRA</h1>
    <p>Law Time Tracker for Kejaksaan</p>
  </div>

  <!-- Form Login -->
  <form class="login-container" method="POST">
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Masuk</button>
  </form>

</body>
</html>
