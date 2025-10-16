<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dealer Motor</title>
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
        
        <!-- Menu tambahan sebelum wishlist -->
        <li class="nav-item">
          <a class="nav-link text-white" href="produk.php">Menu</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="wishlist.php">❤️ Wishlist</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="produk_keranjang.php">
            🛒 Keranjang
            <?php if (!empty($_SESSION['cart'])): ?>
              <span class="badge bg-danger"><?= count($_SESSION['cart']); ?></span>
            <?php endif; ?>
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

<div class="container mt-4">
