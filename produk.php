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
  <title>Daftar Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Motor</h2>
    <a href="produk_keranjang.php" class="btn btn-success">🛒 Lihat Keranjang 
      (<?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0 ?>)
    </a>
  </div>

  <div class="row">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm">

        <img src="admin/<?= $row['photo'] ?>" 
     class="card-img-top" 
     alt="<?= $row['nama_produk'] ?>" 
     style="height:200px; object-fit:cover;">

        <div class="card-body">
          <h5 class="card-title"><?= $row['nama_produk'] ?></h5>
          <p class="text-muted mb-1"><?= $row['nama_merk'] ?></p>
          <p class="small"><?= $row['deskripsi'] ?></p>
          <p class="fw-bold text-primary">Rp <?= number_format($row['harga'],0,',','.') ?></p>
          <a href="produk.php?add=<?= $row['id_produk'] ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
