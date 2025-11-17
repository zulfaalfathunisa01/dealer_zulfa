<?php
include "../db/koneksi.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('ID transaksi tidak ditemukan!'); window.location='?page=transaksi';</script>";
    exit;
}

$id_transaksi = intval($_GET['id']);

// Ambil data transaksi
$queryTransaksi = mysqli_query($koneksi, "
    SELECT t.*, p.nama_pengguna 
    FROM transaksi t 
    LEFT JOIN pengguna p ON t.pengguna_id = p.id_pengguna
    WHERE t.id_transaksi = $id_transaksi
");

$transaksi = mysqli_fetch_assoc($queryTransaksi);

if (!$transaksi) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location='?page=transaksi';</script>";
    exit;
}

// Ambil detail produk
$queryDetail = mysqli_query($koneksi, "
    SELECT td.*, pr.nama_produk 
    FROM transaksi_detail td
    JOIN produk pr ON td.produk_id = pr.id_produk
    WHERE td.transaksi_id = $id_transaksi
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk Transaksi <?= $id_transaksi ?></title>

<style>
    body {
        font-family: "Poppins", Arial, sans-serif;
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .struk-container {
        width: 110mm;
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .header {
        text-align: center;
        border-bottom: 2px dashed #007bff;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .header h2 {
        color: #007bff;
        margin: 5px 0;
        font-size: 20px;
    }

    .info {
        background: #f9f9ff;
        border: 1px solid #007bff;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 18px;
    }

    .info p {
        margin: 5px 0;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-bottom: 10px;
    }

    th, td {
        padding: 6px;
    }

    th {
        background: #007bff;
        color: white;
        text-align: left;
    }

    .subtotal {
        text-align: right;
        color: #007bff;
        font-weight: bold;
    }

    .total-row {
        border-top: 2px dashed #007bff;
        font-weight: bold;
        font-size: 15px;
        color: #007bff;
    }

    .footer {
        text-align: center;
        margin-top: 15px;
        font-size: 13px;
        color: #555;
    }

    .footer strong {
        color: #007bff;
    }

    .btn-area {
        text-align: center;
        margin-top: 20px;
    }

    .btn-area button,
    .btn-area a {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 14px;
        border: none;
        text-decoration: none;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-print {
        background: #007bff;
    }

    .btn-back {
        background: #6c757d;
    }

    @media print {
        .btn-area {
            display: none;
        }
        body {
            background: white;
        }
        .struk-container {
            box-shadow: none;
        }
    }
</style>

</head>
<body>

<div class="struk-container">

    <div class="header">
        <img src="../assets/img/logo.png" width="65" style="border-radius:50%;" onerror="this.style.display='none'">
        <h2>Dealer Motor Zulfoce</h2>
        <small>Jl. Amerta No. 12, Bandung | 0812-3456-7890</small>
    </div>

    <div class="info">
        <p><strong>ID Transaksi:</strong> <?= $transaksi['id_transaksi'] ?></p>
        <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?> WIB</p>
        <p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi['nama_pengguna']) ?></p>
        <p><strong>Status:</strong> <?= ucfirst($transaksi['status']) ?></p>
    </div>

    <!-- DETAIL ITEM -->
    <table border="1">
        <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>

        <?php 
        $total = 0;
        while ($item = mysqli_fetch_assoc($queryDetail)): 
            $sub = $item['jumlah'] * $item['harga'];
            $total += $sub;
        ?>
        <tr>
            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td class="subtotal">Rp <?= number_format($sub, 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>

        <tr class="total-row">
            <td colspan="3">TOTAL</td>
            <td class="subtotal">Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih telah bertransaksi di</p>
        <strong>Dealer Motor Zulfoce</strong>
    </div>

    <div class="btn-area">
        <button class="btn-print" onclick="window.print()">Cetak</button>
        <a href="index.php?page=transaksi" class="btn-back">Kembali</a>
    </div>

</div>

</body>
</html>
