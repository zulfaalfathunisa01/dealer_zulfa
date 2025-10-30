<?php
session_start();
include "header.php";
include "db/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
  echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Tambah ke wishlist
if (isset($_GET['id'])) {
  $id_produk = intval($_GET['id']);

  // Cek apakah produk sudah ada di wishlist user
  $cek = $koneksi->query("SELECT * FROM wishlist WHERE id_pengguna='$id_pengguna' AND id_produk='$id_produk'");
  if ($cek->num_rows == 0) {
    $koneksi->query("INSERT INTO wishlist (id_pengguna, id_produk, tanggal_ditambahkan)
                         VALUES ('$id_pengguna', '$id_produk', NOW())");
  }

  header("Location: profil.php#wishlist-tab");
  exit;
}

// Hapus dari wishlist
if (isset($_GET['hapus'])) {
  $id_produk = intval($_GET['hapus']);
  $koneksi->query("DELETE FROM wishlist WHERE id_pengguna='$id_pengguna' AND id_produk='$id_produk'");
  header("Location: profil.php#wishlist-tab");
  exit;
}
