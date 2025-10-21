<?php
session_start();
include "db/koneksi.php";

// ====== Tambah ke Keranjang ======
if (isset($_GET['add'])) {
    if (!isset($_SESSION['id_pengguna'])) {
        echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
        exit;
    }

    $id_pengguna = $_SESSION['id_pengguna'];
    $id_produk = intval($_GET['add']);
    $qty = 1;

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

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location='produk.php';</script>";
    exit;
}

// ====== Ambil Data Produk ======
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
  <title>Dealer Motor - Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar { box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .card { border: none; transition: transform 0.2s, box-shadow 0.2s; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .card-title { font-size: 1.1rem; }
    .btn-primary { background-color: #0d6efd; border: none; }
    .btn-primary:hover { background-color: #0b5ed7; }
  </style>
</head>
<body>

<!-- 🌐 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">

        <!-- 🔍 Form Pencarian -->
        <li class="nav-item me-3">
          <form class="d-flex" role="search" method="GET" action="index.php">
            <input class="form-control form-control-sm me-2" type="search" name="cari"
                   placeholder="Cari motor..." aria-label="Search" style="width:180px;">
            <button class="btn btn-light btn-sm" type="submit">🔍</button>
          </form>
        </li>

        <!-- 🛒 Keranjang -->
        <li class="nav-item me-3">
          <a class="nav-link text-white position-relative" href="produk_keranjang.php">
            🛒 Keranjang
            <?php
            if (isset($_SESSION['id_pengguna'])) {
              $id_pengguna = $_SESSION['id_pengguna'];
              $q = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM keranjang WHERE id_pengguna = $id_pengguna");
              $d = mysqli_fetch_assoc($q);
              if ($d['jumlah'] > 0) {
                echo "<span class='badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill'>{$d['jumlah']}</span>";
              }
            }
            ?>
          </a>
        </li>

        <!-- 👤 Profil / 🔑 Login -->
        <?php if (isset($_SESSION['id_pengguna'])): ?>
          <li class="nav-item"><a class="nav-link text-white" href="profil.php">👤 Profil</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="logout.php">🚪 Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="login.php">🔑 Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- 📦 Konten Produk -->
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary"> Daftar Motor Tersedia</h2>
  </div>

  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="admin/<?= $row['photo'] ?: 'no-image.png' ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($row['nama_produk']) ?>" 
                 style="height:200px; object-fit:cover;">
            <div class="card-body text-center">
              <h5 class="card-title text-dark mb-1"><?= htmlspecialchars($row['nama_produk']) ?></h5>
              <p class="text-muted mb-1"><?= htmlspecialchars($row['nama_merk']) ?></p>
              <p class="text-secondary small mb-2"><?= htmlspecialchars($row['deskripsi']) ?></p>
              <p class="fw-bold text-success">Rp <?= number_format($row['harga'],0,',','.') ?></p>
              <a href="produk.php?add=<?= $row['id_produk'] ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted">Tidak ada produk ditemukan 😢</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
