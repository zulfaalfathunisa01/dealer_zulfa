<?php
include "../db/koneksi.php";
include "footer.php";
include "header.php";

$id = intval($_GET['id']); 

// hapus keranjang user
$koneksi->query("DELETE FROM keranjang WHERE id_pengguna = $id");

// baru hapus user
$sql = "DELETE FROM pengguna WHERE id_pengguna = $id";
if ($koneksi->query($sql) === TRUE) {
    header('location:index.php?page=user');
    exit;
} else {
    echo "❌ Gagal menghapus user: " . $koneksi->error;
}

?>
