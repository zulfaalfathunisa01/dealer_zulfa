<?php
session_start();
include "db/koneksi.php"; // sesuaikan path koneksi

// Query ambil produk
$sql = "SELECT m.id_produk, m.nama_produk, mk.nama_merk, m.deskripsi, m.harga, m.photo
        FROM produk m
        LEFT JOIN merk mk ON m.merk_id = mk.id_merk
        ORDER BY m.id_produk DESC";
$result = $koneksi->query($sql);

// Kalau tombol Add to Cart ditekan
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    $sql = "SELECT * FROM produk WHERE id_produk = $id";
    $res = $koneksi->query($sql);
    $produk = $res->fetch_assoc();

    if ($produk) {
        // kalau belum ada di cart
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'nama'  => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'qty'   => 1
            ];
        } else {
            $_SESSION['cart'][$id]['qty'] += 1;
        }
    }
    header("Location: produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dealer Motor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Dealer Motor</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      
      <!-- Form pencarian -->
      <form class="d-flex ms-auto me-3" action="index.php" method="get">
        <input class="form-control me-2" type="search" name="cari" placeholder="Cari motor..." aria-label="Search">
        <button class="btn btn-light" type="submit">Cari</button>
      </form>

      <!-- Menu kanan -->
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
        <?php if (isset($_SESSION['id_pengguna'])): ?>
        
        <li class="nav-item">
          <a class="nav-link text-white" href="produk.php">🏍️ Menu</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="wishlist.php">❤️ Wishlist</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="produk_keranjang.php">🛒 Keranjang</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="pesanan_saya.php">📦 Pesanan Saya</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="riwayat.php">🧾 Riwayat</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="logout.php">🚪 Logout</a>
        </li>

        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="login.php">🔑 Login</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- Akhir Navbar -->

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Motor</h2>
    <a href="produk_keranjang.php" class="btn btn-success">
      🛒 Lihat Keranjang 
      (<?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0 ?>)
    </a>
  </div>

  <div class="row">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm border-0">
        <img src="admin/<?= $row['photo'] ?>" 
             class="card-img-top" 
             alt="<?= htmlspecialchars($row['nama_produk']) ?>" 
             style="height:200px; object-fit:cover; border-radius:6px 6px 0 0;">
        <div class="card-body">
          <h5 class="card-title text-primary"><?= htmlspecialchars($row['nama_produk']) ?></h5>
          <p class="text-muted mb-1"><?= htmlspecialchars($row['nama_merk']) ?></p>
          <p class="small text-secondary"><?= htmlspecialchars($row['deskripsi']) ?></p>
          <p class="fw-bold text-dark">Rp <?= number_format($row['harga'],0,',','.') ?></p>
          <a href="produk.php?add=<?= $row['id_produk'] ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
