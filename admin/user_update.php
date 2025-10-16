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
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center; /* ✅ ini biar card di tengah vertikal & horizontal */
    }

    .edit-card {
      background: #fff;
      padding: 40px 45px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 420px;
      max-width: 90%; /* ✅ biar tetap bagus di HP */
      text-align: left;
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

    textarea {
      resize: vertical;
    }

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

    .btn-submit:hover {
      background: #0b5ed7;
    }

    .back-btn {
      display: inline-block;
      margin-top: 12px;
      text-decoration: none;
      color: #0d6efd;
      font-weight: 500;
    }

    .back-btn:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="edit-card">
    <h2>Edit Data User</h2>
    <form action="" method="post">
      <div class="mb-3">
        <label for="nama_pengguna">Nama Lengkap</label>
        <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?= htmlspecialchars($user['nama_pengguna']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="no_hp">Nomor HP</label>
        <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($user['alamat']) ?></textarea>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn-submit">Simpan Perubahan</button><br>
        <a href="index.php?page=user" class="back-btn">⬅ Kembali ke Daftar User</a>
      </div>
    </form>
  </div>

</body>
</html>
