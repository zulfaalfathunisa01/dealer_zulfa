<?php
session_start();
include "header.php";
include "db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['id_pengguna'])) {
  echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

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
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #f8f9fa;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh; /* biar penuh layar */
      display: flex;
      flex-direction: column;
    }

    /* Header tetap di atas */
    .header-riwayat {
      position: sticky;
      top: 0;
      background: #ffffff;
      z-index: 10;
      padding: 20px 0;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .header-riwayat h2 {
      color: #007bff;
      margin: 0;
    }

    /* Area konten bisa scroll */
    .scroll-area {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      max-height: calc(100vh - 120px); /* biar gak tembus ke bawah */
    }

    .order-item {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 15px;
      transition: transform 0.2s ease;
    }

    .order-item:hover {
      transform: scale(1.01);
    }

    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 8px;
      margin-bottom: 10px;
    }

    .badge {
      padding: 6px 10px;
      border-radius: 6px;
      color: #fff;
      font-size: 13px;
    }
    .bg-warning { background: #ffc107; color: #000; }
    .bg-info { background: #0dcaf0; color: #000; }
    .bg-success { background: #28a745; }
    .bg-danger { background: #dc3545; }

    .btn-detail {
      background: #007bff;
      color: white;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
    }
    .btn-detail:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<div class="header-riwayat">
  <h2>📜 Riwayat Pesanan Saya</h2>
</div>

<div class="scroll-area container">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="order-item">
        <div class="order-header">
          <span><b>ID Transaksi:</b> <?= $row['id_transaksi'] ?></span>
          <span><?= date('l, d-m-Y H:i:s', strtotime($row['tanggal_transaksi'])) ?> WIB</span>
        </div>
        <div class="order-body d-flex justify-content-between align-items-center">
          <div>
            <p><b>Total Harga:</b> Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></p>
            <p>
              <b>Status:</b>
              <?php if ($row['status'] == 'proses'): ?>
                <span class="badge bg-warning">Proses</span>
              <?php elseif ($row['status'] == 'kirim'): ?>
                <span class="badge bg-info">Dikirim</span>
              <?php elseif ($row['status'] == 'selesai'): ?>
                <span class="badge bg-success">Selesai</span>
              <?php else: ?>
                <span class="badge bg-danger">Batal</span>
              <?php endif; ?>
            </p>
          </div>
          <a href="riwayat_detail.php?id=<?= $row['id_transaksi'] ?>" class="btn-detail">Lihat Detail</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align:center; font-size:16px; margin-top:20px;">Belum ada pesanan yang kamu buat 💔</p>
  <?php endif; ?>
</div>

<?php include "footer.php"; ?>
