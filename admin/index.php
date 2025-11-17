<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
    }

    /* Sidebar tetap di kiri, tidak ikut scroll */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      height: 100vh;
      background: #0d6efd;
      color: white;
      padding: 20px;
      overflow-y: auto;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background: #0b5ed7;
    }

    /* Biar isi halaman gak ketimpa sidebar */
    .content {
      margin-left: 240px;
      padding: 20px;
    }

    .card-custom {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 d-none d-md-block sidebar p-3">
        <h4 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h4>
        <a href="index.php"><i class="bi bi-house-door"></i> Home</a>
        <a href="index.php?page=produk"><i class="bi bi-box"></i> Produk</a>
        <a href="?page=merk"><i class="bi bi-list"></i> Merk</a>
        <a href="?page=user"><i class="bi bi-people"></i> User</a>
        <a href="?page=transaksi"><i class="bi bi-cart-check"></i> Pesanan</a>
        <a href="?page=pengaturan"><i class="bi bi-gear"></i> Pengaturan Akun</a>
        <hr class="text-white">
        <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </nav>


      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 content">
        <?php
        $page = $_GET['page'] ?? 'home';
        $file = "$page.php";

        if (file_exists($file)) {
          include $file;
        } else {
          echo "<h2>404 - Halaman tidak ditemukan</h2>";
        }
        ?>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS + Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('salesChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
          datasets: [{
            label: 'Penjualan',
            data: [120, 190, 300, 500, 200, 300],
            borderColor: 'rgba(13, 109, 253, 0.46)',
            backgroundColor: 'rgba(13,110,253,0.2)',
            tension: 0.4,
            fill: true
          }]
        }
      });
    }
  </script>
</body>

</html>