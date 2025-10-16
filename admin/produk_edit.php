<?php
include "../db/koneksi.php";

// Ambil ID produk dari URL
if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan!");
}
$id = $_GET['id'];

// Ambil data produk berdasarkan ID
$result = $koneksi->query("SELECT * FROM produk WHERE id_produk = '$id'");
if (!$result || $result->num_rows == 0) {
    die("Produk tidak ditemukan!");
}
$produk = $result->fetch_assoc();

// Ambil data merk
$merk_result = $koneksi->query("SELECT * FROM merk ORDER BY nama_merk ASC");

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $merk = $_POST['merk'];

    // Jika ada file foto baru
    if (!empty($_FILES["foto"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $foto_name = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . time() . "_" . $foto_name;

       if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
    $photo_sql = ", photo='$target_file'";
} else {
    $photo_sql = "";
}
    } else {
        $photo_sql = "";
    }

    // Update produk
    $sql = "UPDATE produk SET 
                nama_produk='$nama_produk',
                kategori='$kategori',
                merk_id='$merk',
                harga='$harga',
                deskripsi='$deskripsi',
                stock='$stock'
                $photo_sql
            WHERE id_produk='$id'";

    if ($koneksi->query($sql) === TRUE) {
        echo "Produk berhasil diperbarui. <a href='index.php?page=produk'>Kembali</a>";
    } else {
        echo "Error: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; }
    .container {
      width: 600px; margin: 40px auto; background: #fff;
      padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    input, select, textarea {
      width: 100%; padding: 10px; margin-top: 8px;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      margin-top: 15px; padding: 12px; background: #007bff;
      border: none; color: white; border-radius: 5px; cursor: pointer;
    }
    button:hover { background: #0056b3; }
    img { margin-top: 10px; max-height: 150px; display: block; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Produk</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" value="<?= $produk['nama_produk'] ?>" required>

      <label>Kategori</label>
      <select name="kategori" required>
        <option value="Matic" <?= ($produk['kategori'] == 'Matic') ? 'selected' : '' ?>>Matic</option>
        <option value="Classic" <?= ($produk['kategori'] == 'Classic') ? 'selected' : '' ?>>Classic</option>
      </select>

      <label>Merk</label>
      <select name="merk" required>
        <?php while($row = $merk_result->fetch_assoc()): ?>
          <option value="<?= $row['id_merk'] ?>" <?= ($row['id_merk'] == $produk['merk_id']) ? 'selected' : '' ?>>
            <?= $row['nama_merk'] ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label>Harga</label>
      <input type="number" name="harga" value="<?= $produk['harga'] ?>" required>

      <label>Deskripsi</label>
      <textarea name="deskripsi" required><?= $produk['deskripsi'] ?></textarea>

      <label>Stok</label>
      <input type="number" name="stock" value="<?= $produk['stock'] ?>" required>

      <label>Foto Produk</label>
      <?php if (!empty($produk['photo'])): ?>
        <img src="<?= $produk['photo'] ?>" alt="Foto Produk">
      <?php endif; ?>
      <input type="file" name="foto" accept="image/*">

      <button type="submit">Simpan Perubahan</button>
    </form>
  </div>
</body>
</html>
