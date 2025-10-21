<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="mb-4 text-primary fw-bold">📊 Dashboard Penjualan</h2>

  <!-- RINGKASAN PENJUALAN -->
  <div class="row mb-4">
    <?php
    // Total Pendapatan
    $totalPendapatan = $koneksi->query("
      SELECT SUM(td.harga * td.jumlah) AS total
      FROM transaksi_detail td
      JOIN transaksi t ON td.transaksi_id = t.id_transaksi
    ")->fetch_assoc()['total'] ?? 0;

    // Total Transaksi
    $totalTransaksi = $koneksi->query("
      SELECT COUNT(*) AS total FROM transaksi
    ")->fetch_assoc()['total'] ?? 0;

    // Produk Terjual
    $produkTerjual = $koneksi->query("
      SELECT SUM(jumlah) AS total FROM transaksi_detail
    ")->fetch_assoc()['total'] ?? 0;

    // Total Pengguna
    $totalUser = $koneksi->query("
      SELECT COUNT(*) AS total FROM pengguna
    ")->fetch_assoc()['total'] ?? 0;
    ?>

    <div class="row mb-4">
  <?php
  // Total Pendapatan
  $totalPendapatan = $koneksi->query("
    SELECT SUM(td.harga * td.jumlah) AS total
    FROM transaksi_detail td
    JOIN transaksi t ON td.transaksi_id = t.id_transaksi
  ")->fetch_assoc()['total'] ?? 0;

  // Total Transaksi
  $totalTransaksi = $koneksi->query("
    SELECT COUNT(*) AS total FROM transaksi
  ")->fetch_assoc()['total'] ?? 0;

  // Produk Terjual
  $produkTerjual = $koneksi->query("
    SELECT SUM(jumlah) AS total FROM transaksi_detail
  ")->fetch_assoc()['total'] ?? 0;

  // Total Pengguna
  $totalUser = $koneksi->query("
    SELECT COUNT(*) AS total FROM pengguna
  ")->fetch_assoc()['total'] ?? 0;
  ?>

  <style>
    .dashboard-card {
      text-decoration: none;
      color: inherit;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: block;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
  </style>

 <div class="col-md-3 mb-3">
  <a href="index.php?page=pendapatan" class="dashboard-card">
    <div class="card text-center shadow border-0 rounded-4">
      <div class="card-body">
        <h5 class="text-success fw-bold">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h5>
        <p class="text-muted mb-0">Total Pendapatan</p>
      </div>
    </div>
  </a>
</div>


  <div class="col-md-3 mb-3">
    <a href="index.php?page=transaksi" class="dashboard-card">
      <div class="card text-center shadow border-0 rounded-4">
        <div class="card-body">
          <h5 class="text-primary fw-bold"><?= $totalTransaksi ?></h5>
          <p class="text-muted mb-0">Transaksi</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-3 mb-3">
    <a href="index.php?page=produk_terjual" class="dashboard-card">
      <div class="card text-center shadow border-0 rounded-4">
        <div class="card-body">
          <h5 class="text-warning fw-bold"><?= $produkTerjual ?></h5>
          <p class="text-muted mb-0">Produk Terjual</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-3 mb-3">
    <a href="index.php?page=user" class="dashboard-card">
      <div class="card text-center shadow border-0 rounded-4">
        <div class="card-body">
          <h5 class="text-danger fw-bold"><?= $totalUser ?></h5>
          <p class="text-muted mb-0">Total Pengguna</p>
        </div>
      </div>
    </a>
  </div>
</div>

  <!-- TABEL TRANSAKSI TERBARU -->
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <h5 class="fw-bold mb-3">🧾 Transaksi Terbaru</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>ID Transaksi</th>
              <th>Nama Pengguna</th>
              <th>Produk</th>
              <th>Jumlah</th>
              <th>Total Harga</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "
              SELECT 
                t.id_transaksi, 
                p.nama_pengguna AS nama_user, 
                pr.nama_produk, 
                td.jumlah, 
                (td.harga * td.jumlah) AS total_harga, 
                t.tanggal_transaksi
              FROM transaksi t
              LEFT JOIN pengguna p ON t.pengguna_id = p.id_pengguna
              JOIN transaksi_detail td ON t.id_transaksi = td.transaksi_id
              JOIN produk pr ON td.produk_id = pr.id_produk
              ORDER BY t.tanggal_transaksi DESC
              LI
            ";

            $result = $koneksi->query($sql);

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "
                <tr>
                  <td><span class='badge bg-primary'>{$row['id_transaksi']}</span></td>
                  <td>" . htmlspecialchars($row['nama_user'] ?? 'Tidak Diketahui') . "</td>
                  <td>{$row['nama_produk']}</td>
                  <td>{$row['jumlah']}</td>
                  <td><strong>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</strong></td>
                  <td><small class='text-muted'>{$row['tanggal_transaksi']}</small></td>
                </tr>
                ";
              }
            } else {
              echo "<tr><td colspan='6' class='text-center text-muted py-4'>Belum ada transaksi</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
