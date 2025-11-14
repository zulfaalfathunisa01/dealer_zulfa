<?php
session_start();
include "header.php";
include "db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login dulu'); window.location='login.php';</script>";
    exit;
}
$id_pengguna = intval($_SESSION['id_pengguna']);

// Tambah ke keranjang
if (isset($_POST['tambah_keranjang'])) {
    $id_produk = intval($_POST['id_produk']);
    $qty = 1;

    // Cek stok sebelum dimasukkan
    $cek_stok = $koneksi->prepare("SELECT stock FROM produk WHERE id_produk=?");
    $cek_stok->bind_param("i", $id_produk);
    $cek_stok->execute();
    $stok_data = $cek_stok->get_result()->fetch_assoc();

    if ($stok_data['stock'] <= 0) {
        echo "<script>alert('❌ Produk ini sudah habis, tidak bisa ditambahkan ke keranjang.'); window.location='produk_detail.php?id=$id_produk';</script>";
        exit;
    }

    // Cek apakah produk sudah di keranjang
    $cek = $koneksi->prepare("SELECT id_keranjang, qty FROM keranjang WHERE id_pengguna=? AND id_produk=?");
    $cek->bind_param("ii", $id_pengguna, $id_produk);
    $cek->execute();
    $res = $cek->get_result();

    if ($res->num_rows > 0) {
        $r = $res->fetch_assoc();
        $new_qty = $r['qty'] + 1;
        $up = $koneksi->prepare("UPDATE keranjang SET qty=? WHERE id_keranjang=?");
        $up->bind_param("ii", $new_qty, $r['id_keranjang']);
        $up->execute();
    } else {
        $ins = $koneksi->prepare("INSERT INTO keranjang (id_pengguna, id_produk, qty) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $id_pengguna, $id_produk, $qty);
        $ins->execute();
    }
}

// Update qty
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

// Hapus item
if (isset($_GET['hapus'])) {
    $hapus = intval($_GET['hapus']);
    $del = $koneksi->prepare("DELETE FROM keranjang WHERE id_keranjang=? AND id_pengguna=?");
    $del->bind_param("ii", $hapus, $id_pengguna);
    $del->execute();
    header("Location: produk_keranjang.php");
    exit;
}

// Ambil data keranjang + stok
$q = $koneksi->prepare("
    SELECT k.id_keranjang, k.id_produk, k.qty, p.nama_produk, p.harga, p.photo, p.stock
    FROM keranjang k
    JOIN produk p ON k.id_produk = p.id_produk
    WHERE k.id_pengguna = ?
    ORDER BY k.id_keranjang DESC
");
$q->bind_param("i", $id_pengguna);
$q->execute();
$res = $q->get_result();
?>

<!-- 🛍️ HEADER -->
<div class="text-center my-5">
  <h1 class="fw-bold text-primary">Keranjang Belanja Kamu 🛒</h1>
  <p class="text-muted fs-5">Lihat dan kelola produk yang kamu pilih</p>
</div>

<!-- 🧾 ISI KERANJANG -->
<div class="container mb-5">
  <form method="post" action="checkout.php">
    <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>Pilih</th>
            <th>Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Status</th>
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
                  $stok_habis = ($row['stock'] <= 0);
          ?>
          <tr class="<?= $stok_habis ? 'table-danger' : '' ?>">
            <td>
              <?php if (!$stok_habis): ?>
                <input type="checkbox" name="pilih[]" value="<?= $row['id_keranjang'] ?>">
              <?php endif; ?>
            </td>
            <td><img src="admin/<?= htmlspecialchars($row['photo']) ?>" width="80" class="rounded"></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td>
              <?php if ($stok_habis): ?>
                <input type="number" disabled class="form-control text-center" value="<?= $row['qty'] ?>" style="width:80px;margin:auto;">
              <?php else: ?>
                <input type="number" name="qty[<?= $row['id_keranjang'] ?>]" value="<?= $row['qty'] ?>" min="1" class="form-control text-center" style="width:80px;margin:auto;">
              <?php endif; ?>
            </td>
            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            <td>
              <?php if ($stok_habis): ?>
                <span class="badge bg-danger">Stok Habis</span>
              <?php else: ?>
                <span class="badge bg-success">Tersedia</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="?hapus=<?= $row['id_keranjang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin mau hapus produk ini?')">🗑️</a>
            </td>
          </tr>
          <?php endwhile; ?>
          <tr class="table-light fw-bold">
            <td colspan="6" class="text-end">Total Keseluruhan</td>
            <td colspan="2">Rp <?= number_format($total, 0, ',', '.') ?></td>
          </tr>
          <?php else: ?>
          <tr>
            <td colspan="8" class="text-center text-muted">Keranjangmu masih kosong</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between mt-4">
      <button type="submit" name="update" formaction="produk_keranjang.php" class="btn btn-success px-4 rounded-pill">🔄 Update Qty</button>
      <button type="submit" name="checkout_dipilih" class="btn btn-warning px-4 rounded-pill">🧾 Pesan yang Dipilih</button>
    </div>
  </form>
</div>

<?php include "footer.php"; ?>
