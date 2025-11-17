<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "db/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dealer Motor</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Wajib agar ikon mata (bi-eye) muncul -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">Zulforce</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">

        <!-- 🔍 Form Pencarian -->
        <li class="nav-item me-3">
          <form class="d-flex" role="search" method="GET" action="index.php">
            <input 
              class="form-control form-control-sm me-2"
              type="search"
              name="cari"
              placeholder="Cari motor..."
              style="width: 180px;"
              value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>"
            >
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
              $query = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM keranjang WHERE id_pengguna = $id_pengguna");
              $data = mysqli_fetch_assoc($query);
              if ($data['jumlah'] > 0) {
                echo "<span class='badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill'>{$data['jumlah']}</span>";
              }
            }
            ?>
          </a>
        </li>

        <!-- 👤 Profil atau 🔑 Login -->
        <?php if (isset($_SESSION['id_pengguna'])): ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="profil.php">👤 Profil</a>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
