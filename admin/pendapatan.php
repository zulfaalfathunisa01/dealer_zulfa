<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="text-primary mb-4">💰 Detail Total Pendapatan</h2>

  <?php
  // Ambil data transaksi yang sudah selesai
  $query = mysqli_query($koneksi, "
    SELECT id_transaksi, tanggal, total_harga, nama_pelanggan
    FROM transaksi
    WHERE status = 'selesai'
    ORDER BY tanggal DESC
  ");
  ?>

  <table class="table table-bordered table-striped">
    <thead class="table-success">
      <tr>
        <th>ID Transaksi</th>
        <th>Tanggal</th>
        <th>Nama Pelanggan</th>
        <th>Total Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $grandTotal = 0;
      while ($data = mysqli_fetch_assoc($query)) {
        $grandTotal += $data['total_harga'];
      ?>
        <tr>
          <td><?= $data['id_transaksi'] ?></td>
          <td><?= date('d-m-Y', strtotime($data['tanggal'])) ?></td>
          <td><?= $data['nama_pelanggan'] ?? '-' ?></td>
          <td>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td>
        </tr>
      <?php } ?>
      <tr class="table-success fw-bold">
        <td colspan="3" class="text-end">Total Pendapatan</td>
        <td>Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>
</div>
