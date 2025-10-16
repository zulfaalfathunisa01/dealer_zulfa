<?php
session_start();
include "header.php";
include "db/koneksi.php";

// Inisialisasi wishlist di session
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Tambah ke wishlist
if (isset($_GET['id'])) {
    $id_produk = intval($_GET['id']);
    if (!in_array($id_produk, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $id_produk;
    }
    header("Location: wishlist.php");
    exit;
}

// Hapus dari wishlist
if (isset($_GET['hapus'])) {
    $id_produk = intval($_GET['hapus']);
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$id_produk]);
    header("Location: wishlist.php");
    exit;
}

// Ambil data produk dari database
$wishlist_produk = false;
if (!empty($_SESSION['wishlist'])) {
    $ids = implode(",", array_map('intval', $_SESSION['wishlist']));
    $sql = "SELECT * FROM produk WHERE id_produk IN ($ids)";
    $wishlist_produk = $koneksi->query($sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Wishlist Saya</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #f8f9fa;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 90%;
      margin: 30px auto;
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }
    .wishlist-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 15px;
      transition: transform 0.2s ease;
    }
    .wishlist-item:hover {
      transform: scale(1.01);
    }
    .wishlist-item img {
      width: 140px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
    }
    .wishlist-info {
      flex: 1;
      margin-left: 20px;
    }
    .wishlist-info h4 {
      margin: 0 0 5px 0;
      color: #007bff;
      font-size: 18px;
    }
    .wishlist-info p {
      margin: 0;
      color: green;
      font-weight: bold;
      font-size: 16px;
    }
    .wishlist-buttons {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .btn {
      text-decoration: none;
      color: #fff;
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 14px;
      text-align: center;
      transition: background 0.2s ease;
    }
    .btn-keranjang {
      background: #28a745;
    }
    .btn-keranjang:hover {
      background: #218838;
    }
    .btn-hapus {
      background: #dc3545;
    }
    .btn-hapus:hover {
      background: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>💖 Wishlist Saya</h2>

    <?php if ($wishlist_produk && $wishlist_produk->num_rows > 0): ?>
      <?php while ($row = $wishlist_produk->fetch_assoc()): ?>
        <div class="wishlist-item">
          <img src="admin/<?= htmlspecialchars($row['photo']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
          <div class="wishlist-info">
            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
            <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
          </div>
          <div class="wishlist-buttons">
            <a href="produk_keranjang.php?id=<?= $row['id_produk'] ?>" class="btn btn-keranjang">+ Keranjang</a>
            <a href="checkout.php?id=<?= $row['id_produk'] ?>" class="btn btn-keranjang">Checkout</a>
            <a href="wishlist.php?hapus=<?= $row['id_produk'] ?>" class="btn btn-hapus" onclick="return confirm('Hapus dari wishlist?')">Hapus</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center; font-size:16px; margin-top:20px;">Belum ada produk di wishlist kamu 💔</p>
    <?php endif; ?>
  </div>
</body>
</html>

<?php include "footer.php"; ?>
