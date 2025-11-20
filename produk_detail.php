<?php
session_start();
include "db/koneksi.php";
include "header.php";

// Pastikan ada id produk di URL
if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$id_produk = intval($_GET['id']);

// Ambil data produk dari database
$stmt = $koneksi->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();

if (!$produk) {
  echo "<p>Produk tidak ditemukan!</p>";
  include "footer.php";
  exit;
}

// Jika user menambahkan ke wishlist
if (isset($_GET['wishlist']) && isset($_SESSION['id_pengguna'])) {
  $id_pengguna = $_SESSION['id_pengguna'];

  // Cek apakah produk sudah ada di wishlist
  $cek = $koneksi->prepare("SELECT id_wishlist FROM wishlist WHERE id_pengguna = ? AND id_produk = ?");
  $cek->bind_param("ii", $id_pengguna, $id_produk);
  $cek->execute();
  $result = $cek->get_result();

  if ($result->num_rows == 0) {
    $stmt = $koneksi->prepare("INSERT INTO wishlist (id_pengguna, id_produk) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_pengguna, $id_produk);
    $stmt->execute();
  }

  header("Location: wishlist.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($produk['nama_produk']) ?></title>

  <!-- SimpleLightbox CSS -->
  <link href="https://cdn.jsdelivr.net/npm/simplelightbox@2.14.1/dist/simple-lightbox.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f7fa;
      margin: 0;
    }

    .container {
      width: 80%;
      margin: 50px auto;
      padding: 20px;
      display: flex;
      gap: 30px;
      align-items: flex-start;
    }

    .foto {
      flex: 1;
    }

    .foto img {
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s;
    }

    .foto img:hover {
      transform: scale(1.03);
    }

    .detail {
      flex: 2;
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h2 {
      margin-top: 0;
      color: #007bff;
      font-weight: 600;
    }

    .price {
      color: #007bff;
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .stock {
      font-size: 15px;
      color: #28a745;
      font-weight: 500;
      margin-bottom: 20px;
    }

    .stock.habis {
      color: #dc3545;
    }

    .detail p {
      color: #333;
      line-height: 1.8;
      font-size: 15px;
    }

    .btn-group {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 8px 14px;
      text-decoration: none;
      border-radius: 6px;
      color: #fff;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      opacity: 0.9;
      transform: translateY(-1px);
    }

    .btn-keranjang {
      background: #007bff;
    }

    .btn-wishlist {
      background: #0d6efd;
    }

    .btn-checkout {
      background: #0a58ca;
    }

    .btn-login {
      background: #6c757d;
    }

    .btn-disabled {
      background: #6c757d;
      cursor: not-allowed;
      opacity: 0.7;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        width: 90%;
      }

      .btn-group {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="foto">
      <a href="admin/<?= htmlspecialchars($produk['photo']) ?>">
        <img src="admin/<?= htmlspecialchars($produk['photo']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
      </a>
    </div>

    <div class="detail">
      <h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>
      <p class="price">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>

      <!-- Tambahan stok -->
      <?php if ($produk['stock'] > 0): ?>
        <p class="stock">📦 Stok: <?= intval($produk['stock']) ?> unit</p>
      <?php else: ?>
        <p class="stock habis">❌ Stok Habis</p>
      <?php endif; ?>

      <?php
      $bagian = explode("Spesifikasi:", $produk['deskripsi']);
      $isi_deskripsi = isset($bagian[0]) ? trim(str_replace("Deskripsi:", "", $bagian[0])) : '';
      $isi_spesifikasi = isset($bagian[1]) ? trim($bagian[1]) : '';
      ?>

      <div style="margin-bottom:20px;">
        <h4 style="color:#007bff;">Deskripsi</h4>
        <p><?= nl2br(htmlspecialchars($isi_deskripsi)) ?></p>

        <h4 style="color:#007bff; margin-top:15px;">Spesifikasi</h4>
        <p><?= nl2br(htmlspecialchars($isi_spesifikasi)) ?></p>
      </div>

      <div class="btn-group">
  <?php if ($produk['stock'] > 0): ?>
    <!-- Tombol Keranjang -->
    <?php if (isset($_SESSION['id_pengguna'])): ?>
      <form action="produk_keranjang.php" method="POST" style="display:inline;">
        <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
        <button type="submit" class="btn btn-keranjang" name="tambah_keranjang">🛒 Keranjang</button>
      </form>
    <?php else: ?>
      <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-keranjang">
        🛒 Keranjang
      </a>
    <?php endif; ?>

    <!-- Tombol Pesan Sekarang -->
    <?php if (isset($_SESSION['id_pengguna'])): ?>
      <a href="checkout.php?id=<?= $produk['id_produk'] ?>" class="btn btn-checkout">💳 Pesan Sekarang</a>
    <?php else: ?>
      <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-checkout">
        💳 Pesan Sekarang
      </a>
    <?php endif; ?>
  <?php else: ?>
    <!-- Tombol disabled kalau stok habis -->
    <button class="btn btn-disabled" disabled>🛒 Keranjang (Stok Habis)</button>
    <button class="btn btn-disabled" disabled>💳 Pesan Tidak Tersedia</button>
  <?php endif; ?>

  <!-- Tombol Wishlist -->
  <?php if (isset($_SESSION['id_pengguna'])): ?>
    <a href="wishlist.php?id=<?= $produk['id_produk'] ?>&wishlist=1" class="btn btn-wishlist">💖 Wishlist</a>
  <?php else: ?>
    <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-login">🔐 Login Wishlist</a>
  <?php endif; ?>
</div>


  <script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.14.1/dist/simple-lightbox.min.js"></script>
  <script>
    var lightbox = new SimpleLightbox('.foto a', {});
  </script>
</body>

</html>

<?php include "footer.php"; ?>