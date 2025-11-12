<?php
include "../db/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Ambil data dari form
  $nama_produk = trim($_POST['nama_produk']);
  $harga = trim($_POST['harga']);
  $deskripsi = trim($_POST['deskripsi']);
  $spesifikasi = trim($_POST['spesifikasi']);
  $stock = trim($_POST['stock']);
  $merk = $_POST['merk'];
  $kategori = $_POST['kategori'];

  // 🔹 Gabungkan deskripsi dan spesifikasi menjadi satu teks
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

  // Validasi file gambar
  $check = getimagesize($_FILES["foto"]["tmp_name"]);
  if ($check === false) {
    echo "<script>alert('File bukan gambar!');</script>";
    $uploadOk = 0;
  }

  if ($_FILES["foto"]["size"] > 2000000) { // Maks 2MB
    echo "<script>alert('Ukuran file terlalu besar (maks 2MB)!');</script>";
    $uploadOk = 0;
  }

  $allowed = ["jpg", "jpeg", "png"];
  if (!in_array($imageFileType, $allowed)) {
    echo "<script>alert('Hanya format JPG, JPEG, PNG yang diperbolehkan!');</script>";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    echo "<script>alert('Upload gagal, periksa file gambar.');</script>";
  } else {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
      // Simpan ke database
      $sql = "INSERT INTO produk (nama_produk, merk_id, harga, deskripsi, stock, kategori, photo) 
              VALUES ('$nama_produk', '$merk', '$harga', '$deskripsi_gabung', '$stock', '$kategori', '$target_file')";

      if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Motor berhasil ditambahkan!'); window.location='index.php?page=produk';</script>";
      } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
      }
    } else {
      echo "<script>alert('Terjadi kesalahan saat mengupload file.');</script>";
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
      font-family: "Poppins", sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 650px;
      margin: 50px auto;
      background: #ffffff;
      padding: 30px 35px;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #1a1a1a;
      font-weight: 700;
    }
    label {
      font-weight: 600;
      display: block;
      margin-top: 15px;
      color: #333;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
      box-sizing: border-box;
    }
    textarea {
      resize: vertical;
    }
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
    button:hover {
      background: #0056b3;
    }
    .back-btn {
      display: inline-block;
      margin-bottom: 10px;
      color: #007bff;
      text-decoration: none;
      font-weight: 600;
    }
    .back-btn:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Tambah Motor Baru</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <label for="nama_produk">Nama Produk</label>
      <input type="text" id="nama_produk" name="nama_produk" required>

      <label for="merk">Merk</label>
      <select id="merk" name="merk" required>
        <option value="">-- Pilih Merk --</option>
        <?php while($row = $merk_result->fetch_assoc()): ?>
          <option value="<?= $row['id_merk'] ?>"><?= $row['nama_merk'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="harga">Harga (Rp)</label>
      <input type="number" id="harga" name="harga" required>

      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Tulis deskripsi umum motor..." required></textarea>

      <label for="spesifikasi">Spesifikasi</label>
      <textarea id="spesifikasi" name="spesifikasi" rows="4" placeholder="Tulis spesifikasi teknis motor (contoh: mesin, kapasitas, rem, dll)..." required></textarea>

      <label for="stock">Stok</label>
      <input type="number" id="stock" name="stock" required>

      <label for="kategori">Kategori</label>
      <select id="kategori" name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="classic">Classic</option>
        <option value="matic">Matic</option>
      </select>

      <label for="foto">Foto Produk</label>
      <input type="file" id="foto" name="foto" accept="image/*" required>

      <button type="submit">Upload Motor</button>
    </form>
  </div>

</body>
</html>
