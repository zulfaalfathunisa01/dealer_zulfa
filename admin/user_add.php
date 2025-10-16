<?php
include "../db/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $email  = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp  = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // Simpan ke database
    $sql = "INSERT INTO pengguna (nama_pengguna, email, no_hp, alamat) 
            VALUES ('$nama', '$email', '$no_hp', '$alamat')";

    if ($koneksi->query($sql) === TRUE) {
        header('location:index.php?page=user');
    } else {
        echo "❌ Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload User</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #222;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      margin-top: 20px;
      padding: 12px;
      width: 100%;
      background: #007bff;
      color: #fff;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #007bff;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Form Upload User</h2>
    <form action="" method="post">
      <label for="nama_pengguna">Nama</label>
      <input type="text" id="nama_pengguna" name="nama_pengguna" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="no_hp">No HP</label>
      <input type="text" id="no_hp" name="no_hp" required>

      <label for="alamat">Alamat</label>
      <textarea id="alamat" name="alamat" rows="3" required></textarea>

      <button type="submit">Simpan User</button>
    </form>
  </div>

</body>
</html>
