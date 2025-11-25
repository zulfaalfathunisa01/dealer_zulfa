<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="text-primary mb-4">💰Total Pendapatan</h2>

  <!-- 🔹 Form Filter -->
  <form method="GET" action="">
    <!-- biar nggak balik ke dashboard -->
    <input type="hidden" name="page" value="pendapatan">

    <div class="input-group mb-4" style="max-width: 400px;">
      <label class="input-group-text bg-primary text-white">Filter</label>
      <select name="filter" class="form-select" onchange="this.form.submit()">
        <option value="bulan" <?= (isset($_GET['filter']) && $_GET['filter'] == 'bulan') ? 'selected' : '' ?>>Per Bulan</option>
        <option value="minggu" <?= (isset($_GET['filter']) && $_GET['filter'] == 'minggu') ? 'selected' : '' ?>>Per Minggu</option>
        <option value="tahun" <?= (isset($_GET['filter']) && $_GET['filter'] == 'tahun') ? 'selected' : '' ?>>Per Tahun</option>
      </select>
    </div>
  </form>

  <?php
  // Ambil filter (default per bulan)
  $filter = isset($_GET['filter']) ? $_GET['filter'] : 'bulan';

  // Buat query sesuai filter
  if ($filter == 'minggu') {
      // 🔹 Kelompokkan per minggu
      $sql = "
        SELECT 
          YEAR(tanggal_transaksi) AS tahun,
          WEEK(tanggal_transaksi) AS minggu,
          SUM(total_harga) AS total_pendapatan
        FROM transaksi
        WHERE status IN ('selesai', 'kirim', 'proses', 'batal')
        GROUP BY tahun, minggu
        ORDER BY tahun DESC, minggu DESC
      ";
  } elseif ($filter == 'tahun') {
      // 🔹 Kelompokkan per tahun
      $sql = "
        SELECT 
          YEAR(tanggal_transaksi) AS tahun,
          SUM(total_harga) AS total_pendapatan
        FROM transaksi
        WHERE status IN ('selesai', 'kirim', 'proses', 'batal')
        GROUP BY tahun
        ORDER BY tahun DESC
      ";
  } else {
      // 🔹 Default: per bulan
      $sql = "
        SELECT 
          YEAR(tanggal_transaksi) AS tahun,
          MONTH(tanggal_transaksi) AS bulan,
          SUM(total_harga) AS total_pendapatan
        FROM transaksi
        WHERE status IN ('selesai', 'kirim', 'proses', 'batal')
        GROUP BY tahun, bulan
        ORDER BY tahun DESC, bulan DESC
      ";
  }

  $query = mysqli_query($koneksi, $sql);

  if (!$query) {
      die("<div class='alert alert-danger'>Query Error: " . mysqli_error($koneksi) . "</div>");
  }

  if (mysqli_num_rows($query) > 0) {
      echo "<table class='table table-bordered table-striped'>
              <thead class='table-primary'>
                <tr>";

      if ($filter == 'minggu') {
          echo "<th>Tahun</th><th>Minggu Ke-</th><th>Total Pendapatan</th>";
      } elseif ($filter == 'tahun') {
          echo "<th>Tahun</th><th>Total Pendapatan</th>";
      } else {
          echo "<th>Tahun</th><th>Bulan</th><th>Total Pendapatan</th>";
      }

      echo "  </tr>
              </thead>
              <tbody>";

      $totalSemua = 0;

      while ($data = mysqli_fetch_assoc($query)) {
          echo "<tr>";
          if ($filter == 'minggu') {
              echo "<td>{$data['tahun']}</td>
                    <td>Minggu ke-{$data['minggu']}</td>
                    <td>Rp " . number_format($data['total_pendapatan'], 0, ',', '.') . "</td>";
          } elseif ($filter == 'tahun') {
              echo "<td>{$data['tahun']}</td>
                    <td>Rp " . number_format($data['total_pendapatan'], 0, ',', '.') . "</td>";
          } else {
              // ubah angka bulan jadi nama bulan
              $bulanNama = date("F", mktime(0, 0, 0, $data['bulan'], 1));
              echo "<td>{$data['tahun']}</td>
                    <td>{$bulanNama}</td>
                    <td>Rp " . number_format($data['total_pendapatan'], 0, ',', '.') . "</td>";
          }
          echo "</tr>";

          $totalSemua += $data['total_pendapatan'];
      }

      echo "</tbody></table>";

      echo "<div class='alert alert-success fs-5 mt-3'>
              💵 <strong>Total Keseluruhan Pendapatan:</strong> Rp " . number_format($totalSemua, 0, ',', '.') . "
            </div>";
  } else {
      echo "<div class='alert alert-warning'>Belum ada transaksi yang selesai atau dikirim.</div>";
  }
  ?>
</div>
