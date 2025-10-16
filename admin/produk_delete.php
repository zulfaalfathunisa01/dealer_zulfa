<?php
include "../db/koneksi.php";

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan!");
}

$id = $_GET['id'];

// Ambil data produk dulu (untuk hapus file foto juga kalau ada)
$result = $koneksi->query("SELECT photo FROM produk WHERE id_produk='$id'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (!empty($row['photo']) && file_exists($row['photo'])) {
        unlink($row['photo']); // hapus file gambar
    }
}

// Hapus produk dari database
$sql = "DELETE FROM produk WHERE id_produk='$id'";
if ($koneksi->query($sql) === TRUE) {
   header('location:index.php?page=produk');
} else {
    echo "Error menghapus produk: " . $koneksi->error;
    
}
?>
