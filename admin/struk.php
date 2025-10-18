<?php
include "../db/koneksi.php";

if (!isset($_GET['id'])) {
  echo "<script>alert('ID transaksi tidak ditemukan!'); window.location='?page=transaksi';</script>";
  exit;
}

$id_transaksi = intval($_GET['id']);
$queryTransaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi");
$transaksi = mysqli_fetch_assoc($queryTransaksi);

if (!$transaksi) {
  echo "<script>alert('Transaksi tidak ditemukan!'); window.location='?page=transaksi';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Transaksi #<?= $id_transaksi ?></title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: "Poppins", Arial, sans-serif;
      background: linear-gradient(135deg, #f0f4ff, #ffffff);
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .struk-container {
      background: #fff;
      width: 110mm;
      padding: 25px;
      border-radius: 14px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      transition: 0.3s;
    }
    .struk-container:hover {
      transform: scale(1.01);
      box-shadow: 0 14px 30px rgba(0,0,0,0.25);
    }
    .header {
      text-align: center;
      border-bottom: 2px dashed #007bff;
      padding-bottom: 12px;
      margin-bottom: 18px;
    }
    .header img {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      margin-bottom: 5px;
    }
    .header h2 {
      font-size: 22px;
      color: #007bff;
      margin: 0;
    }
    .header h4 {
      font-size: 14px;
      margin: 2px 0;
      color: #666;
    }

    .info {
      background: #f9f9ff;
      border: 1px solid #007bff;
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .info p {
      font-size: 14px;
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      margin-top: 12px;
    }
    td {
      padding: 7px 4px;
    }
    .total {
      font-weight: bold;
      font-size: 15px;
      color: #007bff;
      border-top: 2px dashed #007bff;
      border-bottom: 2px solid #007bff;
      margin-top: 12px;
    }
    .footer {
      text-align: center;
      margin-top: 15px;
      font-size: 13px;
      color: #666;
    }
    .footer strong {
      color: #007bff;
    }
    .footer p {
      margin: 4px 0;
    }

    .print-btn {
      text-align: center;
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    .print-btn button {
      background: linear-gradient(90deg, #007bff, #00bfff);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.25);
      transition: 0.3s;
    }
    .print-btn button:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #0069d9, #0090ff);
    }

    .back-btn {
      background: linear-gradient(90deg, #6c757d, #8a8f94);
    }
    .back-btn:hover {
      background: linear-gradient(90deg, #5a6268, #6c757d);
    }

    @media print {
      body {
        background: #fff;
      }
      .struk-container {
        box-shadow: none;
        border-radius: 0;
      }
      .print-btn { display: none; }
    }
  </style>
</head>
<body>
  <div class="struk-container">
    <div class="header">
      <img src="../assets/img/logo.png" alt="Logo Dealer" onerror="this.style.display='none'">
      <h2>Dealer Motor Zulfoce</h2>
      <h4>Jl. Amerta No. 12, Bandung</h4>
      <small>Telp: 0812-3456-7890</small>
    </div>

    <div class="info">
      <p><strong>ID Transaksi:</strong> <?= $transaksi['id_transaksi'] ?></p>
      <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?></p>
      <p><strong>ID Pengguna:</strong> <?= $transaksi['pengguna_id'] ?></p>
      <p><strong>Status:</strong> <?= ucfirst($transaksi['status']) ?></p>
    </div>

    <table>
      <tr class="total">
        <td>Total Pembayaran</td>
        <td style="text-align:right;">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
      </tr>
    </table>

    <div class="footer">
      <p>💙 Terima kasih telah bertransaksi 💙</p>
      <p><strong>Dealer Motor Zulfoce</strong></p>
    </div>

    <div class="print-btn">
      <button onclick="window.print()">🖨️ Cetak Struk</button>
      <button class="back-btn" onclick="window.location.href='transaksi.php'">⬅️ Kembali</button>
    </div>
  </div>
</body>
</html>
