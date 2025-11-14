<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../db/koneksi.php";

// Pastikan form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {

    // Ambil data dari form
    $id_pengguna = isset($_POST['id_pengguna']) ? intval($_POST['id_pengguna']) : 0;
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $no_hp = $koneksi->real_escape_string($_POST['no_hp']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);
    $catatan = isset($_POST['catatan']) ? $koneksi->real_escape_string($_POST['catatan']) : '';

    // Pastikan ada produk yang diproses
    if (empty($_POST['id_produk']) || empty($_POST['jumlah'])) {
        die("Tidak ada produk untuk diproses.");
    }

    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $total_semua = 0;

    // Hitung total harga
    foreach ($id_produk as $i => $pid) {
        $pid = intval($pid);
        $qty = intval($jumlah[$i]);

        $result = $koneksi->query("SELECT harga FROM produk WHERE id_produk = $pid");
        if ($result->num_rows > 0) {
            $harga = $result->fetch_assoc()['harga'];
            $total_semua += $harga * $qty;
        }
    }

    // Tambahkan transaksi utama
    $admin_id = 1; // sementara admin default
    $tanggal = date('Y-m-d H:i:s');
    $status = 'proses';

    if ($id_pengguna <= 0) {
        die("Gagal: ID pengguna tidak ditemukan. Pastikan pengguna login sebelum checkout.");
    }

    $sql_transaksi = "
        INSERT INTO transaksi (pengguna_id, admin_id, tanggal_transaksi, total_harga, status)
        VALUES ('$id_pengguna', '$admin_id', '$tanggal', '$total_semua', '$status')
    ";

    if (!$koneksi->query($sql_transaksi)) {
        die("Gagal menyimpan transaksi: " . $koneksi->error);
    }

    $id_transaksi = $koneksi->insert_id;

    // Simpan detail transaksi dan kurangi stok
    foreach ($id_produk as $i => $pid) {
        $pid = intval($pid);
        $qty = intval($jumlah[$i]);

        // Ambil data produk
        $produk = $koneksi->query("SELECT harga, stock FROM produk WHERE id_produk = $pid")->fetch_assoc();
        if (!$produk) {
            die("Produk dengan ID $pid tidak ditemukan.");
        }

        $harga = $produk['harga'];
        $stok_sekarang = $produk['stock'];

        // Cek stok cukup
        if ($stok_sekarang < $qty) {
            echo "<script>alert('Stok produk tidak mencukupi untuk produk ID $pid!'); window.location='../produk_keranjang.php';</script>";
            exit;
        }

        // Simpan detail transaksi
        $sql_detail = "
            INSERT INTO transaksi_detail (transaksi_id, produk_id, jumlah, harga)
            VALUES ('$id_transaksi', '$pid', '$qty', '$harga')
        ";

        if (!$koneksi->query($sql_detail)) {
            die("Gagal menyimpan detail transaksi: " . $koneksi->error);
        }

        // Kurangi stok produk
       $qtyupdate = $koneksi->query("UPDATE produk SET stock = stock - $qty WHERE id_produk = $pid");

 
    }

    // Hapus produk dari keranjang
    if (isset($_POST['id_keranjang'])) {
        foreach ($_POST['id_keranjang'] as $idk) {
            $idk = intval($idk);
            $koneksi->query("DELETE FROM keranjang WHERE id_keranjang = $idk AND id_pengguna = $id_pengguna");
        }
    }

    // ✅ Notifikasi setelah berhasil checkout
    echo '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout Berhasil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg text-center p-5" style="max-width: 500px;">
            <h3 class="text-success mb-3">✅ Pesanan Berhasil!</h3>
            <p>Terima kasih, <strong>' . htmlspecialchars($nama) . '</strong>.<br>
            Pesananmu sedang diproses oleh admin.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="../profil.php" class="btn btn-primary">Lihat Pesanan Saya</a>
                <a href="../index.php" class="btn btn-outline-secondary">Kembali ke Produk</a>
            </div>
        </div>
    </body>
    </html>
    ';
    exit;
}
?>
