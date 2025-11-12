<?php
include "../db/koneksi.php";

$error_harga = "";
$error_stock = "";
$error_upload = "";
$error_nama = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Ambil data dari form
  $nama_produk = trim($_POST['nama_produk']);
  $harga = trim($_POST['harga']);
  $deskripsi = trim($_POST['deskripsi']);
  $spesifikasi = trim($_POST['spesifikasi']);
  $stock = trim($_POST['stock']);
  $merk = $_POST['merk'];
  $kategori = $_POST['kategori'];

  // 🔒 Validasi harga & stok
  if ($harga < 1) {
    $error_harga = "Harga tidak boleh kurang dari 1";
  }
  if ($stock < 1) {
    $error_stock = "Stok tidak boleh kurang dari 1";
  }

  // 🔎 Cek duplikat nama produk
  $cek_duplikat = $koneksi->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk = ?");
  $cek_duplikat->bind_param("s", $nama_produk);
  $cek_duplikat->execute();
  $cek_duplikat->bind_result($jumlah);
  $cek_duplikat->fetch();
  $cek_duplikat->close();

  if ($jumlah > 0) {
    $error_nama = "Produk dengan nama ini sudah ada!";
  }

  // ✅ Lanjut jika tidak ada error
  if (empty($error_harga) && empty($error_stock) && empty($error_nama)) {
    // Gabungkan deskripsi & spesifikasi
    $deskripsi_gabung = "Deskripsi:\n$deskripsi\n\nSpesifikasi:\n$spesifikasi";

    // Upload foto
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $foto_name = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . time() . "_" . $foto_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi gambar
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
      $error_upload = "File bukan gambar.";
      $uploadOk = 0;
    }

    if ($_FILES["foto"]["size"] > 2000000) {
      $error_upload = "Ukuran file terlalu besar (maks 2MB).";
      $uploadOk = 0;
    }

    $allowed = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $allowed)) {
      $error_upload = "Hanya format JPG, JPEG, PNG yang diperbolehkan.";
      $uploadOk = 0;
    }

    if ($uploadOk == 1 && empty($error_upload)) {
      if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Simpan ke database
        $sql = "INSERT INTO produk (nama_produk, merk_id, harga, deskripsi, stock, kategori, photo)
                VALUES ('$nama_produk', '$merk', '$harga', '$deskripsi_gabung', '$stock', '$kategori', '$target_file')";
        if ($koneksi->query($sql) === TRUE) {
          header('Location: index.php?page=produk');
          exit;
        } else {
          $error_upload = "Gagal menyimpan ke database: " . $koneksi->error;
        }
      } else {
        $error_upload = "Terjadi error saat upload file.";
      }
    }
  }
}

$merk_result = $koneksi->query("SELECT * FROM merk ORDER BY nama_merk ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Motor Dealer</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 650px;
      margin: 40px auto;
      background: #fff;
      padding: 25px 30px;
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
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
      box-sizing: border-box;
    }
    textarea { resize: vertical; }
    button {
      margin-top: 25px;
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
    button:hover { background: #0056b3; }
    .error-text {
      color: red;
      font-size: 13px;
      margin-top: 4px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Upload Motor Dealer</h2>

    <form action="" method="post" enctype="multipart/form-data">
      <label for="nama_produk">Nama Produk</label>
      <input type="text" id="nama_produk" name="nama_produk" required value="<?= isset($nama_produk) ? htmlspecialchars($nama_produk) : '' ?>">
      <?php if ($error_nama): ?>
        <div class="error-text"><?= $error_nama ?></div>
      <?php endif; ?>

      <label for="merk">Merk</label>
      <select id="merk" name="merk" required>
        <option value="">-- Pilih Merk --</option>
        <?php while($row = $merk_result->fetch_assoc()): ?>
          <option value="<?= $row['id_merk'] ?>" <?= (isset($merk) && $merk == $row['id_merk']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['nama_merk']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label for="harga">Harga (Rp)</label>
      <input type="number" id="harga" name="harga" min="1" required value="<?= isset($harga) ? htmlspecialchars($harga) : '' ?>">
      <?php if ($error_harga): ?>
        <div class="error-text"><?= $error_harga ?></div>
      <?php endif; ?>

      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="3" required><?= isset($deskripsi) ? htmlspecialchars($deskripsi) : '' ?></textarea>

      <label for="spesifikasi">Spesifikasi</label>
      <textarea id="spesifikasi" name="spesifikasi" rows="4" required><?= isset($spesifikasi) ? htmlspecialchars($spesifikasi) : '' ?></textarea>

      <label for="stock">Stok</label>
      <input type="number" id="stock" name="stock" min="1" required value="<?= isset($stock) ? htmlspecialchars($stock) : '' ?>">
      <?php if ($error_stock): ?>
        <div class="error-text"><?= $error_stock ?></div>
      <?php endif; ?>

      <label for="kategori">Kategori</label>
      <select id="kategori" name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="classic" <?= (isset($kategori) && $kategori=="classic") ? "selected" : "" ?>>Classic</option>
        <option value="matic" <?= (isset($kategori) && $kategori=="matic") ? "selected" : "" ?>>Matic</option>
      </select>

      <label for="foto">Foto</label>
      <input type="file" id="foto" name="foto" accept="image/*" required>
      <?php if ($error_upload): ?>
        <div class="error-text"><?= $error_upload ?></div>
      <?php endif; ?>

      <button type="submit">Upload Motor</button>
    </form>
  </div>

</body>
</html>
