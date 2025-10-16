<?php
session_start();
include "db/koneksi.php";

// pastikan user login
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}
$id_pengguna = intval($_SESSION['id_pengguna']);

// tambah ke keranjang
if (isset($_POST['tambah_keranjang'])) {
  $id_produk = intval($_POST['id_produk']);
  $qty = 1;
  $cek = $koneksi->prepare("SELECT id_keranjang, qty FROM keranjang WHERE id_pengguna=? AND id_produk=?");
  $cek->bind_param("ii", $id_pengguna, $id_produk);
  $cek->execute();
  $res = $cek->get_result();
  if ($res->num_rows > 0) {
    $r = $res->fetch_assoc();
    $new_qty = $r['qty'] + 1;
    $up = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=?");
    $up->bind_param("ii", $new_qty, $r['id_keranjang']);
    $up->execute();
  } else {
    $ins = $koneksi->prepare("INSERT INTO keranjang (id_pengguna, id_produk, qty) VALUES (?, ?, ?)");
    $ins->bind_param("iii", $id_pengguna, $id_produk, $qty);
    $ins->execute();
  }
}

// update qty
if (isset($_POST['update'])) {
  foreach ($_POST['qty'] as $id_keranjang => $qty) {
    $id_keranjang = intval($id_keranjang);
    $qty = intval($qty);
    if ($qty > 0) {
      $up = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=? AND id_pengguna=?");
      $up->bind_param("iii", $qty, $id_keranjang, $id_pengguna);
      $up->execute();
    }
  }
  header("Location: produk_keranjang.php");
  exit;
}

// hapus item
if (isset($_GET['hapus'])) {
  $hapus = intval($_GET['hapus']);
  $del = $koneksi->prepare("DELETE FROM keranjang WHERE id_keranjang=? AND id_pengguna=?");
  $del->bind_param("ii", $hapus, $id_pengguna);
  $del->execute();
  header("Location: produk_keranjang.php");
  exit;
}

// ambil data keranjang
$q = $koneksi->prepare("
  SELECT k.id_keranjang, k.id_produk, k.qty, p.nama_produk, p.harga, p.photo
  FROM keranjang k
  JOIN produk p ON k.id_produk = p.id_produk
  WHERE k.id_pengguna = ?
  ORDER BY k.id_keranjang DESC
");
$q->bind_param("i", $id_pengguna);
$q->execute();
$res = $q->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang | Dealer Motor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

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
          <a class="nav-link text-white" href="produk.php">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="wishlist.php">❤️ Wishlist</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active" href="produk_keranjang.php">
            🛒 Keranjang
          </a>
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

<!-- Konten Keranjang -->
<div class="container mt-4 mb-5">
  <h3 class="text-center mb-4">🛒 Keranjang Belanja Kamu</h3>

  <form method="post" action="checkout.php">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>Pilih</th>
            <th>Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          if ($res && $res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
              $subtotal = $row['harga'] * $row['qty'];
              $total += $subtotal;
          ?>
              <tr>
                <td><input type="checkbox" name="pilih[]" value="<?= $row['id_keranjang'] ?>"></td>
                <td><img src="admin/<?= htmlspecialchars($row['photo']) ?>" width="70" class="rounded"></td>
                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td>
                  <input type="number" name="qty[<?= $row['id_keranjang'] ?>]" value="<?= $row['qty'] ?>" min="1" class="form-control text-center" style="width:80px;margin:auto;">
                </td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                <td>
                  <a href="produk_keranjang.php?hapus=<?= $row['id_keranjang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
            <tr class="table-light fw-bold">
              <td colspan="5" class="text-end">Total Keseluruhan</td>
              <td colspan="2">Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Keranjang masih kosong 😢</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between mt-3">
      <button type="submit" name="update" formaction="produk_keranjang.php" class="btn btn-success">🔄 Update Qty</button>
      <button type="submit" name="checkout_dipilih" class="btn btn-warning">🧾 Checkout yang Dipilih</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
