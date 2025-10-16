<?php
session_start();
include "db/koneksi.php";

// pastikan user login
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}
$id_pengguna = intval($_SESSION['id_pengguna']);

if (isset($_POST['tambah_keranjang'])) {
  $id_produk = intval($_POST['id_produk']);
  $qty = 1; // default 1 kalau dari tombol

  // cek apakah produk sudah ada di keranjang
  $cek = $koneksi->prepare("SELECT id_keranjang, qty FROM keranjang WHERE id_pengguna = ? AND id_produk = ?");
  $cek->bind_param("ii", $id_pengguna, $id_produk);
  $cek->execute();
  $result = $cek->get_result();

  if ($result->num_rows > 0) {
    // produk sudah ada → tambah qty
    $row = $result->fetch_assoc();
    $new_qty = $row['qty'] + 1;

    $update = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=?");
    $update->bind_param("ii", $new_qty, $row['id_keranjang']);
    $update->execute();
  } else {
    // produk belum ada → tambahkan baris baru
    $insert = $koneksi->prepare("INSERT INTO keranjang (id_pengguna, id_produk, qty) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $id_pengguna, $id_produk, $qty);
    $insert->execute();
  }
}

// update qty
if (isset($_POST['update'])) {
  foreach ($_POST['qty'] as $id_keranjang => $qty) {
    $id_keranjang = intval($id_keranjang);
    $qty = intval($qty);
    if ($qty > 0) {
      $up = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=? AND id_pengguna=?");
      $up->bind_param("iii", $qty, $id_keranjang, $id_pengguna);
      $up->execute();
    }
  }
  header("Location: produk_keranjang.php");
  exit;
}

// ambil keranjang
$q = $koneksi->prepare("
    SELECT k.id_keranjang, k.id_produk, k.qty, p.nama_produk, p.harga, p.photo
    FROM keranjang k
    JOIN produk p ON k.id_produk = p.id_produk
    WHERE k.id_pengguna = ?
    ORDER BY k.id_keranjang DESC
");
$q->bind_param("i", $id_pengguna);
$q->execute();
$res = $q->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-4">
    <h2>Keranjang Belanja</h2>

    <!-- form kirim ke checkout -->
    <form method="post" action="checkout.php">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Pilih</th>
            <th>Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          if ($res && $res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
              $subtotal = $row['harga'] * $row['qty'];
              $total += $subtotal;
          ?>
              <tr>
                <td>
                  <input type="checkbox" name="pilih[]" value="<?= $row['id_keranjang'] ?>">
                </td>
                <td><img src="admin/<?= htmlspecialchars($row['photo']) ?>" width="70" style="border-radius:6px;"></td>
                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td>
                  <input type="number" name="qty[<?= $row['id_keranjang'] ?>]" value="<?= $row['qty'] ?>" min="1" class="form-control" style="width:80px;">
                </td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                <td>
                  <a href="produk_keranjang.php?hapus=<?= $row['id_keranjang'] ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
            <tr>
              <td colspan="5" class="text-end"><strong>Total Keranjang</strong></td>
              <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
            </tr>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">Keranjang masih kosong.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between">
        <button type="submit" name="update" formaction="produk_keranjang.php" class="btn btn-success">Update Qty</button>
        <button type="submit" name="checkout_dipilih" class="btn btn-warning">Checkout yang Dipilih</button>
      </div>
    </form>
  </div>
</body>

</html>