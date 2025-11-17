<?php
include "../db/koneksi.php";
session_start();

$id = $_GET['id'] ?? null;
$alasan = $_GET['alasan'] ?? '';

if (!$id) {
    echo "<script>alert('ID transaksi tidak ditemukan!'); history.back();</script>";
    exit;
}

if (trim($alasan) == "") {
    echo "<script>alert('Alasan pembatalan wajib diisi!'); history.back();</script>";
    exit;
}

// Ambil data transaksi
$q = $koneksi->query("SELECT id_produk, jumlah, status FROM transaksi WHERE id_transaksi='$id'");
$data = $q->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); history.back();</script>";
    exit;
}

if ($data['status'] == 'batal') {
    echo "<script>alert('Transaksi sudah dibatalkan sebelumnya!'); history.back();</script>";
    exit;
}

$id_produk = $data['id_produk'];
$jumlah    = $data['jumlah'];

// Kembalikan stok
$koneksi->query("UPDATE produk SET stock = stock + $jumlah WHERE id_produk='$id_produk'");

// Update status + catatan admin
$alasan = $koneksi->real_escape_string($alasan);
$koneksi->query("
    UPDATE transaksi 
    SET status='batal', catatan_batal='$alasan' 
    WHERE id_transaksi='$id'
");

echo "<script>
alert('Transaksi berhasil dibatalkan. Alasan telah disimpan.');
window.location='index.php?page=transaksi';
</script>";
?>
