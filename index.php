<?php 
session_start();
include "header.php"; 
include "db/koneksi.php";

// Cek apakah ada pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// Query pencarian
if ($cari != '') {
    $sql = "
        SELECT p.*, m.nama_merk 
        FROM produk p
        LEFT JOIN merk m ON p.merk_id = m.id_merk
        WHERE p.nama_produk LIKE '%$cari%'
           OR m.nama_merk LIKE '%$cari%'
           OR p.kategori LIKE '%$cari%'
        ORDER BY p.id_produk DESC
    ";
} else {
    $sql = "
    SELECT p.*, m.nama_merk 
    FROM produk p
    LEFT JOIN merk m ON p.merk_id = m.id_merk
    WHERE p.id_produk IN (36, 38, 40, 42)
    ORDER BY p.id_produk ASC
";
}

$result = $koneksi->query($sql);
?>

<!-- Hero Section -->
<div class="text-center my-5">
  <h1 class="fw-bold text-primary">Selamat Datang di <span class="text-dark">ZULFORCE</span> 🚀</h1>
  <p class="text-muted fs-5">Temukan motor impianmu dengan mudah dan cepat 💨</p>
</div>

<!-- Hasil Produk -->
<div class="container mb-5">
  <div class="row g-3"><!-- g-3 untuk jarak antar card -->
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3"><!-- ukuran card lebih kecil -->
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            <?php if (!empty($row['photo'])): ?>
              <img src="admin/<?= htmlspecialchars($row['photo']) ?>" 
                   class="card-img-top" 
                   style="height:180px; object-fit:cover;" 
                   alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <?php else: ?>
              <img src="no-image.png" 
                   class="card-img-top" 
                   style="height:180px; object-fit:cover;" 
                   alt="No Image">
            <?php endif; ?>

            <div class="card-body text-center p-3">
              <h6 class="card-title text-dark fw-bold mb-1" style="font-size:15px;">
                <?= htmlspecialchars($row['nama_produk']) ?>
              </h6>
              <p class="text-muted mb-0" style="font-size:13px;">
                <strong>Merk:</strong> <?= htmlspecialchars($row['nama_merk']) ?>
              </p>
              <p class="text-muted mb-1" style="font-size:13px;">
                <strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?>
              </p>
              <p class="text-success fw-bold mt-2 mb-1" style="font-size:14px;">
                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
              </p>
              <a href="produk_detail.php?id=<?= $row['id_produk'] ?>" 
                 class="btn btn-outline-primary rounded-pill btn-sm px-3 mt-1">
                 Lihat Detail
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center mt-4">
        <div class="alert alert-warning shadow-sm border-0 rounded-3">
         Produk tidak ditemukan. Coba kata kunci lain ya
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tombol Lihat Semua -->
  <?php if ($cari == ''): ?>
  <div class="text-center mt-5">
    <a href="produk.php" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
      🔍 Lihat Semua Produk
    </a>
  </div>
  <?php endif; ?>
</div>

<?php include "footer.php"; ?>
