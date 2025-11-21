<?php
include __DIR__ . "/../db/koneksi.php";

// Pastikan ada ID
if (!isset($_GET['id'])) {
    die("<h3>ID User tidak ditemukan!</h3>");
}

$id = intval($_GET['id']);

// =========================
// 1. Ambil data user
// =========================
$stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("<h3>User tidak ditemukan!</h3>");
}

// =========================
// 2. Proses Update Data
// =========================
if (isset($_POST['update'])) {

    $nama   = trim($_POST['nama_pengguna']);
    $email  = trim($_POST['email']);
    $no_hp  = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    // Jika password ingin diganti
    $password_baru = trim($_POST['password_baru']);
    $konfirmasi    = trim($_POST['konfirmasi_password']);

    if ($password_baru !== "" && $password_baru !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {

        // Update data tanpa password
        $sql = $koneksi->prepare("
            UPDATE pengguna 
            SET nama_pengguna=?, email=?, no_hp=?, alamat=? 
            WHERE id_pengguna=?
        ");
        $sql->bind_param("ssssi", $nama, $email, $no_hp, $alamat, $id);
        $sql->execute();

        // Kalau password diisi → update password
        if ($password_baru !== "") {
            $pass = password_hash($password_baru, PASSWORD_DEFAULT);
            $u = $koneksi->prepare("UPDATE pengguna SET password=? WHERE id_pengguna=?");
            $u->bind_param("si", $pass, $id);
            $u->execute();
        }

        echo "<script>
                alert('Data user berhasil diperbarui!');
                window.location='index.php?page=user';
              </script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      background: #fff;
      margin: 40px auto;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #444;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 7px;
      border-radius: 8px;
      border: 1px solid #aaa;
    }
    button {
  width: 100%;
  padding: 12px;
  background: #007bff;
  color: #fff;
  border: none;
  font-size: 16px;
  border-radius: 8px;
  margin-top: 20px;
  cursor: pointer;
}
button:hover {
  background: #0069d9;
}

    .btn-back {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #555;
      text-decoration: none;
    }
  </style>

</head>
<body>

<div class="container">
  <h2>Edit User</h2>

  <form method="post">
    <label>Nama Pengguna</label>
    <input type="text" name="nama_pengguna" value="<?= htmlspecialchars($data['nama_pengguna']) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>" required>

    <label>Alamat</label>
    <textarea name="alamat" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>

    <hr style="margin: 20px 0;">

    <label>Password Baru (opsional)</label>
    <input type="password" name="password_baru" placeholder="Kosongkan jika tidak diganti">

    <label>Konfirmasi Password Baru</label>
    <input type="password" name="konfirmasi_password" placeholder="Ulangi password baru">

    <button type="submit" name="update">Simpan Perubahan</button>
  </form>
</div>

</body>
</html>
