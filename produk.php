<?php
session_start();
include "db/koneksi.php";

// ========== ADD TO CART DULU ==========
if (isset($_GET['add'])) {
    if (!isset($_SESSION['id_pengguna'])) {
        echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
        exit;
    }

    $id_pengguna = $_SESSION['id_pengguna'];
    $id_produk = intval($_GET['add']);
    $qty = 1;

    // Cek apakah produk sudah ada di keranjang user
    $cek = $koneksi->prepare("SELECT id_keranjang, qty FROM keranjang WHERE id_pengguna=? AND id_produk=?");
    $cek->bind_param("ii", $id_pengguna, $id_produk);
    $cek->execute();
    $res = $cek->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $new_qty = $row['qty'] + 1;
        $up = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=?");
        $up->bind_param("ii", $new_qty, $row['id_keranjang']);
        $up->execute();
    } else {
        $ins = $koneksi->prepare("INSERT INTO keranjang (id_pengguna, id_produk, qty) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $id_pengguna, $id_produk, $qty);
        $ins->execute();
    }

    echo "<script>alert('Produk ditambahkan ke keranjang!'); window.location='produk.php';</script>";
    exit;
}


// ========== SETELAH ITU BARU AMBIL PRODUK ==========
$sql = "SELECT m.id_produk, m.nama_produk, mk.nama_merk, m.deskripsi, m.harga, m.photo
        FROM produk m
        LEFT JOIN merk mk ON m.merk_id = mk.id_merk
        ORDER BY m.id_produk DESC";
$result = $koneksi->query($sql);
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
