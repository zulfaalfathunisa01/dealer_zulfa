<?php
session_start();
include "db/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
    exit;
}

// Pastikan ada ID transaksi yang dikirim
if (!isset($_GET['id'])) {
    echo "<script>alert('ID transaksi tidak ditemukan!'); window.location='riwayat.php';</script>";
    exit;
}

$id_transaksi = intval($_GET['id']);

// Ambil data transaksi
$queryTransaksi = "
SELECT t.*, u.nama_pengguna 
FROM transaksi t 
JOIN pengguna u ON t.pengguna_id = u.id_pengguna
WHERE t.id_transaksi = '$id_transaksi'
";
$resultTransaksi = $koneksi->query($queryTransaksi);
$transaksi = $resultTransaksi->fetch_assoc();

// Ambil detail transaksi + produk
$queryDetail = "
SELECT d.*, p.nama_produk, p.harga AS harga_produk, (d.harga * d.jumlah) AS subtotal
FROM transaksi_detail d
JOIN produk p ON d.produk_id = p.id_produk
WHERE d.transaksi_id = '$id_transaksi'
";
$resultDetail = $koneksi->query($queryDetail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Transaksi #<?= $id_transaksi ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <a href="riwayat.php" class="btn btn-secondary mb-3">⬅ Kembali</a>

  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">🧾 Detail Transaksi #<?= $id_transaksi ?></h4>
    </div>
    <div class="card-body">
      <?php if ($transaksi): ?>
      <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?></p>
      <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($transaksi['nama_pengguna']) ?></p>
      <p><strong>Status:</strong> 
        <?php if ($transaksi['status'] == 'proses'): ?>
          <span class="badge bg-warning text-dark">Proses</span>
        <?php elseif ($transaksi['status'] == 'kirim'): ?>
          <span class="badge bg-info text-dark">Dikirim</span>
        <?php elseif ($transaksi['status'] == 'selesai'): ?>
          <span class="badge bg-success">Selesai</span>
        <?php else: ?>
          <span class="badge bg-danger">Batal</span>
        <?php endif; ?>
      </p>
      <?php endif; ?>
      
      <hr>

      <h5 class="mb-3">🛍️ Daftar Produk</h5>
      <?php if ($resultDetail->num_rows > 0): ?>
      <table class="table table-bordered table-hover">
        <thead class="table-primary text-center">
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php 
          $no = 1;
          $total = 0;
          while ($row = $resultDetail->fetch_assoc()): 
            $subtotal = $row['harga_produk'] * $row['jumlah'];
            $total += $subtotal;
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td>Rp <?= number_format($row['harga_produk'], 0, ',', '.') ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-end">Total:</th>
            <th class="text-center text-success">Rp <?= number_format($total, 0, ',', '.') ?></th>
          </tr>
        </tfoot>
      </table>
      <?php else: ?>
        <div class="alert alert-warning text-center">Tidak ada detail produk untuk transaksi ini.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
