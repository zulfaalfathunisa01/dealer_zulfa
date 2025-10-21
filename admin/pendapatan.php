<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="text-primary mb-4">💰 Detail Total Pendapatan</h2>

  <?php
  // Ambil data transaksi yang sudah selesai atau dikirim
  $sql = "
    SELECT id_transaksi, tanggal_transaksi AS tanggal, total_harga, pengguna_id, status
    FROM transaksi
    WHERE status IN ('selesai', 'kirim', 'batal', 'proses')
    ORDER BY tanggal_transaksi DESC
  ";

  $query = mysqli_query($koneksi, $sql);

  if (!$query) {
      die("<div class='alert alert-danger'>Query Error: " . mysqli_error($koneksi) . "</div>");
  }

  // Cek apakah ada data
  if (mysqli_num_rows($query) > 0) {
      $totalPendapatan = 0;

      echo "<table class='table table-bordered table-striped'>
              <thead class='table-primary'>
                <tr>
                  <th>ID Transaksi</th>
                  <th>Tanggal</th>
                  <th>Total Harga</th>
                  <th>ID Pengguna</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>";

      while ($data = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$data['id_transaksi']}</td>
                  <td>{$data['tanggal']}</td>
                  <td>Rp " . number_format($data['total_harga'], 0, ',', '.') . "</td>
                  <td>{$data['pengguna_id']}</td>
                  <td><span class='badge bg-success text-white'>{$data['status']}</span></td>
                </tr>";

          // Hitung total
          $totalPendapatan += $data['total_harga'];
      }

      echo "</tbody></table>";

      // Tampilkan total pendapatan
      echo "<div class='alert alert-success fs-5 mt-3'>
              💵 <strong>Total Pendapatan:</strong> Rp " . number_format($totalPendapatan, 0, ',', '.') . "
            </div>";
  } else {
      echo "<div class='alert alert-warning'>Belum ada transaksi yang selesai atau dikirim.</div>";
  }
  ?>
</div>
