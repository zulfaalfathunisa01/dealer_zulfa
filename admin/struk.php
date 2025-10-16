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
      font-family: "Courier New", monospace;
      background: #f7f7f7;
      color: #000;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* biar posisinya di tengah layar penuh */
      margin: 0;
    }
    .struk-container {
      background: #fff;
      width: 80mm;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }
    .header {
      text-align: center;
      border-bottom: 2px dashed #000;
      padding-bottom: 5px;
      margin-bottom: 10px;
    }
    .header img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 5px;
    }
    h2 {
      font-size: 18px;
      margin: 0;
    }
    h4 {
      font-size: 13px;
      margin: 2px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    td, th {
      padding: 3px;
    }
    .info p {
      font-size: 13px;
      margin: 3px 0;
    }
    .total {
      font-weight: bold;
      border-top: 1px dashed #000;
      border-bottom: 2px solid #000;
      font-size: 14px;
      padding-top: 6px;
    }
    .footer {
      text-align: center;
      margin-top: 10px;
      font-size: 12px;
    }
    .footer p {
      margin: 3px 0;
    }
    .print-btn {
      text-align: center;
      margin-top: 15px;
    }
    .print-btn button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
    }
    .print-btn button:hover {
      background-color: #0056b3;
    }
    @media print {
      body {
        display: block;
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
    </div>
  </div>
</body>
</html>
