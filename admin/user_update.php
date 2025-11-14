<?php
include "../db/koneksi.php";

// --- Ambil data user berdasarkan ID ---
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = $id");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
    } else {
        echo "<script>alert('Data user tidak ditemukan!'); window.location='index.php?page=user';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID user tidak ditemukan!'); window.location='index.php?page=user';</script>";
    exit;
}

// --- Proses update ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $email  = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp  = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // ✅ Cek apakah data sudah digunakan oleh user lain
    $cek_duplikat = mysqli_query($koneksi, "
        SELECT * FROM pengguna 
        WHERE (email='$email' OR no_hp='$no_hp' OR nama_pengguna='$nama')
        AND id_pengguna != $id
    ");

    if (mysqli_num_rows($cek_duplikat) > 0) {
        echo "<script>alert('❌ Gagal! Data sudah digunakan oleh user lain.'); history.back();</script>";
        exit;
    }

    // Jika tidak duplikat → lanjut update
    $sql = "UPDATE pengguna 
            SET nama_pengguna='$nama', email='$email', no_hp='$no_hp', alamat='$alamat' 
            WHERE id_pengguna=$id";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('✅ Data user berhasil diperbarui!'); window.location='index.php?page=user';</script>";
        exit;
    } else {
        echo "❌ Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User | ZULFORCE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .center-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .edit-card {
      background: #fff;
      padding: 40px 45px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 420px;
      max-width: 90%;
      text-align: left;
      animation: fadeIn 0.4s ease;
    }

    h2 {
      text-align: center;
      color: #0d6efd;
      font-weight: 700;
      margin-bottom: 25px;
    }

    label {
      font-weight: 500;
      color: #2c3e50;
      margin-top: 10px;
    }

    input, textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ced4da;
      border-radius: 8px;
      margin-top: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }

    textarea { resize: vertical; }

    .btn-container {
      text-align: center;
      margin-top: 25px;
    }

    .btn-submit {
      background: #0d6efd;
      color: #fff;
      border: none;
      padding: 12px 40px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn-submit:hover { background: #0b5ed7; }

    .back-btn {
      display: inline-block;
      margin-top: 12px;
      text-decoration: none;
      color: #0d6efd;
      font-weight: 500;
    }

    .back-btn:hover { text-decoration: underline; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="center-wrapper">
    <div class="edit-card">
      <h2>Edit Data User</h2>
      <form action="" method="post">
        <div class="mb-3">
          <label for="nama_pengguna">Nama Lengkap</label>
          <input type="text" id="nama_pengguna" name="nama_pengguna" 
                value="<?= htmlspecialchars($user['nama_pengguna'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" 
                value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label for="no_hp">Nomor HP</label>
          <input type="text" id="no_hp" name="no_hp" 
                value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label for="alamat">Alamat</label>
          <textarea id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
        </div>

        <div class="btn-container">
          <button type="submit" class="btn-submit">Simpan Perubahan</button><br>
          <a href="index.php?page=user" class="back-btn">⬅ Kembali ke Daftar User</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
