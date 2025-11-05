<?php
include "../db/koneksi.php";
session_start();

$id = $_GET['id'] ?? null;
if (!$id) {
  echo "<script>alert('ID transaksi tidak ditemukan!'); history.back();</script>";
  exit;
}

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

// Tambahkan stok kembali
$koneksi->query("UPDATE produk SET stock = stock + $jumlah WHERE id_produk='$id_produk'");
$koneksi->query("UPDATE transaksi SET status='batal' WHERE id_transaksi='$id'");

echo "<script>alert('Transaksi dibatalkan. Stok dikembalikan.'); window.location='index.php?page=transaksi';</script>";
?>
