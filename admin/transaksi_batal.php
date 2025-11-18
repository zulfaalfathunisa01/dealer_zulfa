<?php
include "../db/koneksi.php";
session_start();

if (isset($_POST['submit_batal'])) {
    $id_transaksi = intval($_POST['id_transaksi']);
    $catatan = trim($_POST['catatan_batal']);

    // Ambil status transaksi
    $q_status = $koneksi->query("SELECT status FROM transaksi WHERE id_transaksi='$id_transaksi'");
    $data_status = $q_status->fetch_assoc();

    if (!$data_status) {
        echo "<script>alert('Data transaksi tidak ditemukan!'); history.back();</script>";
        exit;
    }

    if ($data_status['status'] == 'batal') {
        echo "<script>alert('Transaksi sudah dibatalkan sebelumnya!'); history.back();</script>";
        exit;
    }

    // Ambil semua produk di transaksi_detail
    $q_detail = $koneksi->query("SELECT produk_id, jumlah FROM transaksi_detail WHERE transaksi_id='$id_transaksi'");
    while ($row = $q_detail->fetch_assoc()) {
        $produk_id = $row['produk_id'];
        $jumlah = $row['jumlah'];
        // Kembalikan stok
        $koneksi->query("UPDATE produk SET stock = stock + $jumlah WHERE id_produk='$produk_id'");
    }

    // Update transaksi: status + catatan
    $stmt = $koneksi->prepare("UPDATE transaksi SET status='batal', catatan_batal=? WHERE id_transaksi=?");
    $stmt->bind_param("si", $catatan, $id_transaksi);
    $stmt->execute();

    echo "<script>alert('Transaksi dibatalkan. Stok dikembalikan.'); window.location='index.php?page=transaksi';</script>";
}
?>
