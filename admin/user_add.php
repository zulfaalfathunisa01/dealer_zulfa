<?php
include __DIR__ . "/../db/koneksi.php";

if (isset($_POST['simpan'])) {

    $nama   = trim($_POST['nama_pengguna']);
    $email  = trim($_POST['email']);
    $no_hp  = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);
    $password = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi_password']);

    // Cek password cocok
    if ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {

        // Cek email apakah sudah dipakai
        $cek = $koneksi->prepare("SELECT * FROM pengguna WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $res = $cek->get_result();

        if ($res->num_rows > 0) {
            echo "<script>alert('Email \"$email\" sudah terdaftar!');</script>";
        } else {

            // Hash password agar aman
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user baru
            $sql = $koneksi->prepare("
                INSERT INTO pengguna (nama_pengguna, email, no_hp, alamat, password)
                VALUES (?, ?, ?, ?, ?)
            ");

            $sql->bind_param("sssss", $nama, $email, $no_hp, $alamat, $password_hash);

            if ($sql->execute()) {
                echo "<script>
                        alert('User berhasil ditambahkan!');
                        window.location='index.php?page=user';
                      </script>";
                exit;
            } else {
                echo "<script>alert('Gagal menambah user: {$koneksi->error}');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      margin: 0; padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 25px 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #222;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 7px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      border: none;
      background: #007bff;
      color: #fff;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover {
      background: #005fcc;
    }
    .back {
      display: block;
      margin-top: 12px;
      text-align: center;
      color: #444;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Tambah User Baru</h2>

  <form method="POST">

    <label>Nama Pengguna</label>
    <input type="text" name="nama_pengguna" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>No HP</label>
    <input type="text" name="no_hp" required>

    <label>Alamat</label>
    <textarea name="alamat" rows="3" required></textarea>

    <hr style="margin: 20px 0;">

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Konfirmasi Password</label>
    <input type="password" name="konfirmasi_password" required>

    <button type="submit" name="simpan">Simpan User</button>
  </form>

  <a href="index.php?page=user" class="back">← Kembali</a>
</div>

</body>
</html>
