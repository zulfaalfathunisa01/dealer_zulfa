<?php

include "../db/koneksi.php";

if($_SERVER["REQUEST_METHOD"] ==="POST"){
// Ambil data dari form
$nama_produk = $_POST['nama_produk'];
$harga   = $_POST['harga'];
$deskripsi      = $_POST['deskripsi'];
$stock      = $_POST['stock'];
$merk      = $_POST['merk'];

// Upload foto
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$foto_name = basename($_FILES["foto"]["name"]);
$target_file = $target_dir . time() . "_" . $foto_name; // kasih timestamp biar unik
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validasi file gambar
$check = getimagesize($_FILES["foto"]["tmp_name"]);
if($check === false) {
    echo "File bukan gambar.";
    $uploadOk = 0;
}

if ($_FILES["foto"]["size"] > 2000000) { // max 2MB
    echo "Ukuran file terlalu besar.";
    $uploadOk = 0;
}

$allowed = ["jpg", "jpeg", "png"];
if(!in_array($imageFileType, $allowed)) {
    echo "Hanya format JPG, JPEG, PNG yang diperbolehkan.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Upload gagal.";
} else {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Simpan ke database
        $sql = "INSERT INTO produk (nama_produk, merk_id, harga, deskripsi, stock, photo) 
                VALUES ('$nama_produk', '$merk', '$harga', '$deskripsi','$stock', '$target_file')";
        if ($koneksi->query($sql) === TRUE) {
            echo "Motor berhasil diupload.<br>";
            header('location:index.php?page=produk');
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } else {
        echo "Terjadi error saat upload file.";
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
  <link rel="stylesheet" href="style.css">
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
    input, select {
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
      background: #0056b3;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Upload Motor Dealer</h2>
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

      <label for="deskripsi">deskripsi</label>
      <input type="text" id="deskripsi" name="deskripsi" required>

      <label for="stock">stock</label>
      <input type="text" id="stock" name="stock" required>
      
      <label for="kategori">kategori</label>
      <select id="kategori" name="kategori" required>
        <option value="classic">Clasik</option>
        <option value="matic">Matic</option>
      </select>

      <label for="photo">Photo</label>
      <input type="file" id="foto" name="foto" accept="image/*" required>

      <button type="submit">Upload Motor</button>
    </form>
  </div>

</body>
</html>
