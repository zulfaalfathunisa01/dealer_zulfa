<?php 
session_start();
include "header.php"; 
include "db/koneksi.php";

// Cek apakah ada pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// Query pencarian
if ($cari != '') {
    $sql = "SELECT p.*, m.nama_merk 
            FROM produk p
            LEFT JOIN merk m ON p.merk_id = m.id_merk
            WHERE p.nama_produk LIKE '%$cari%'
               OR m.nama_merk LIKE '%$cari%'
               OR p.kategori LIKE '%$cari%'
            ORDER BY p.id_produk DESC";
} else {
    // Query default: produk terbaru
    $sql = "SELECT p.*, m.nama_merk 
            FROM produk p
            LEFT JOIN merk m ON p.merk_id = m.id_merk
            ORDER BY p.id_produk DESC LIMIT 6";
}

$result = $koneksi->query($sql);
?>

<h2 class="text-center mb-4">Selamat Datang di ZULFORCE</h2>
<p class="text-center mb-5">Temukan motor impian Anda dengan mudah 🚀</p>

<!-- Hasil Produk -->
<div class="row">
  <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($row['photo'])): ?>
            <img src="admin/<?= $row['photo'] ?>" class="card-img-top" style="height:300px; object-fit:cover;" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
          <?php else: ?>
            <img src="no-image.png" class="card-img-top" style="height:300px; object-fit:cover;" alt="No Image">
          <?php endif; ?>
          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
            <p class="mb-1"><strong>Merk:</strong> <?= htmlspecialchars($row['nama_merk']) ?></p>
            <p class="mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></p>
            <p class="text-success fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
            <a href="produk_detail.php?id=<?= $row['id_produk'] ?>" class="btn btn-primary">Lihat Detail</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center text-muted">Produk tidak ditemukan 😢</p>
  <?php endif; ?>
</div>

<!-- Tombol Lihat Semua -->
<?php if ($cari == ''): ?>
<div class="text-center mt-4">
  <a href="produk.php" class="btn btn-outline-primary">Lihat Semua Produk</a>
</div>
<?php endif; ?>

<?php include "footer.php"; ?>
