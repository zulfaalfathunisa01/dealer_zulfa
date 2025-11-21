<?php
session_start();
include "db/koneksi.php";

// ===== Pastikan user login =====
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$items = [];
$total_semua = 0;

// ===== Nomor booking otomatis =====
$nomor_booking = 'BK' . date('YmdHis') . rand(100, 999);

// ===== Ambil produk langsung via GET (opsional) =====
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $jumlah    = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;

    $produk = $koneksi->query("SELECT * FROM produk WHERE id_produk = $id_produk")->fetch_assoc();
    $produk['jumlah'] = $jumlah;
    $items[] = $produk;
}

// ===== Ambil produk yang dipilih dari keranjang =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout_dipilih'])) {

    if (!isset($_POST['pilih'])) {
        echo "<script>
                alert('Pilih dulu produk yang ingin di-checkout!');
                window.location='produk_keranjang.php';
              </script>";
        exit;
    }

    $id_keranjang = $_POST['pilih'];
    $qty          = $_POST['qty'];

    $items = [];
    foreach ($id_keranjang as $idk) {
        $data = $koneksi->query("
            SELECT k.id_keranjang, k.id_produk, p.nama_produk, p.harga, p.photo
            FROM keranjang k
            JOIN produk p ON k.id_produk = p.id_produk
            WHERE k.id_keranjang = $idk
        ")->fetch_assoc();

        $data['jumlah']   = $qty[$idk];
        $data['subtotal'] = $data['harga'] * $data['jumlah'];

        $items[] = $data;
        $total_semua += $data['subtotal'];
    }
}

// ===== Hitung total harga =====
foreach ($items as $it) {
    $total_semua += $it['harga'] * $it['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | ZULFORCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f4f8; margin: 0; padding: 40px 0; }
        .checkout-card { background: #fff; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 850px; margin: auto; padding: 30px 40px; }
        h2 { text-align: center; color: #0d6efd; font-weight: 700; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th, td { padding: 12px; text-align: center; border-bottom: 1px solid #eee; }
        th { background-color: #0d6efd; color: #fff; font-weight: 600; }
        tr:hover { background-color: #f9fbff; }
        .total { text-align: right; font-weight: 600; font-size: 1.1em; margin-top: 15px; }
        .form-section h3 { color: #0d6efd; font-weight: 600; margin-top: 30px; margin-bottom: 15px; }
        label { font-weight: 500; margin-top: 10px; }
        input[type="text"], input[type="tel"], textarea { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 8px; margin-top: 5px; font-size: 14px; }
        .btn-submit { width: 100%; background: #0d6efd; color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 16px; font-weight: 600; margin-top: 25px; transition: background 0.3s ease; }
        .btn-submit:hover { background: #0b5ed7; }
    </style>
</head>

<body>
    <div class="checkout-card">
        <h2>Checkout Pesanan Anda</h2>

        <form method="post" action="admin/transaksi_add.php">
            <!-- ===== Nomor Booking ===== -->
            <div class="mb-3">
                <label class="form-label">Nomor Booking</label>
                <input type="text" class="form-control" value="<?= $nomor_booking ?>" readonly>
                <input type="hidden" name="nomor_booking" value="<?= $nomor_booking ?>">
            </div>

            <!-- ===== Tabel Produk ===== -->
            <table class="table table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td><?= htmlspecialchars($it['nama_produk']) ?></td>
                            <td>Rp<?= number_format($it['harga'], 0, ',', '.') ?></td>
                            <td><?= $it['jumlah'] ?></td>
                            <td>Rp<?= number_format($it['harga'] * $it['jumlah'], 0, ',', '.') ?></td>
                        </tr>

                        <!-- ===== Hidden Inputs ===== -->
                        <input type="hidden" name="id_pengguna" value="<?= $_SESSION['id_pengguna'] ?>">
                        <input type="hidden" name="id_produk[]" value="<?= $it['id_produk'] ?>">
                        <input type="hidden" name="jumlah[]" value="<?= $it['jumlah'] ?>">
                        <?php if (isset($it['id_keranjang'])): ?>
                            <input type="hidden" name="id_keranjang[]" value="<?= $it['id_keranjang'] ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- ===== Total Harga ===== -->
            <div class="total">
                Total: <span class="text-primary fw-bold">Rp<?= number_format($total_semua, 0, ',', '.') ?></span>
            </div>

            <!-- ===== Form Data Customer ===== -->
            <div class="form-section">
                <h3>Data Customer</h3>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="tel" id="no_hp" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap Anda" required></textarea>
                </div>

                <button type="submit" name="checkout" class="btn-submit">Proses Pesanan</button>
            </div>
        </form>
    </div>
</body>
</html>
