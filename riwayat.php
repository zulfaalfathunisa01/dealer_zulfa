<?php
session_start();
date_default_timezone_set('Asia/Jakarta'); // Set waktu ke WIB

include "db/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data transaksi milik user
$query = "
SELECT t.id_transaksi, t.tanggal_transaksi, t.total_harga, t.status
FROM transaksi t
WHERE t.pengguna_id = '$id_pengguna'
ORDER BY t.tanggal_transaksi DESC
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pesanan Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

  <!-- Tombol Kembali -->
  <a href="index.php" class="btn btn-secondary mb-3">⬅ Kembali</a>

  <h2 class="mb-4 text-center text-primary fw-bold">📜 Riwayat Pesanan Saya</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover shadow-sm bg-white">
      <thead class="table-dark text-center">
        <tr>
          <th>ID Transaksi</th>
          <th>Tanggal</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id_transaksi'] ?></td>
          <td><?= date('l, d-m-Y H:i:s', strtotime($row['tanggal_transaksi'])) ?> WIB</td> <!-- Tampil lengkap hari, tanggal, jam -->
          <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
          <td>
            <?php if ($row['status'] == 'proses'): ?>
              <span class="badge bg-warning text-dark">Proses</span>
            <?php elseif ($row['status'] == 'kirim'): ?>
              <span class="badge bg-info text-dark">Dikirim</span>
            <?php elseif ($row['status'] == 'selesai'): ?>
              <span class="badge bg-success">Selesai</span>
            <?php else: ?>
              <span class="badge bg-danger">Batal</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="riwayat_detail.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-sm btn-primary">
              <i class="bi bi-eye"></i> Detail
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada pesanan yang kamu buat</div>
  <?php endif; ?>
</div>

</body>
</html>
