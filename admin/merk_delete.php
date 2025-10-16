<?php
include "../db/koneksi.php";

// pastikan ada parameter id
if (!isset($_GET['id'])) {
    die("ID merk tidak ditemukan!");
}

$id = intval($_GET['id']); // amankan biar angka

// hapus data merk
$sql = "DELETE FROM merk WHERE id_merk = $id";

if ($koneksi->query($sql) === TRUE) {
    header("Location: merk.php?status=deleted");
    exit;
} else {
    echo "❌ Gagal menghapus merk: " . $koneksi->error;
}
?>
