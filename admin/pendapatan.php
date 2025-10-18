<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="text-primary mb-4">💰 Detail Total Pendapatan</h2>

  <?php
  // Ambil data transaksi yang sudah selesai
  $sql = "
    SELECT id_transaksi, tanggal_transaksi AS tanggal, total_harga, pengguna_id
    FROM transaksi
    WHERE status = 'selesai'
    ORDER BY tanggal_transaksi DESC
  ";

  $query = mysqli_query($koneksi, $sql);

  // Tambahkan pengecekan error
  if (!$query) {
      die("<div class='alert alert-danger'>Query Error: " . mysqli_error($koneksi) . "</div>");
  }

  // Tampilkan datanya
  if (mysqli_num_rows($query) > 0) {
      echo "<table class='table table-bordered table-striped'>
              <thead class='table-primary'>
                <tr>
                  <th>ID Transaksi</th>
                  <th>Tanggal</th>
                  <th>Total Harga</th>
                  <th>ID Pengguna</th>
                </tr>
              </thead>
              <tbody>";
      while ($data = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$data['id_transaksi']}</td>
                  <td>{$data['tanggal']}</td>
                  <td>Rp " . number_format($data['total_harga'], 0, ',', '.') . "</td>
                  <td>{$data['pengguna_id']}</td>
                </tr>";
      }
      echo "</tbody></table>";
  } else {
      echo "<div class='alert alert-warning'>Belum ada transaksi selesai.</div>";
  }
  ?>
</div>
