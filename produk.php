<?php
session_start();
include "header.php";
include "db/koneksi.php";

// Ambil kata kunci pencarian (kalau ada)
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// Query untuk menampilkan produk
if ($cari != '') {
    $sql = "
        SELECT p.*, m.nama_merk 
        FROM produk p
        LEFT JOIN merk m ON p.merk_id = m.id_merk
        WHERE p.nama_produk LIKE '%$cari%'
           OR m.nama_merk LIKE '%$cari%'
           OR p.kategori LIKE '%$cari%'
           OR p.deskripsi LIKE '%$cari%'
        ORDER BY p.id_produk DESC
    ";
} else {
    $sql = "
        SELECT p.*, m.nama_merk 
        FROM produk p
        LEFT JOIN merk m ON p.merk_id = m.id_merk
        ORDER BY p.id_produk DESC
    ";
}

$result = $koneksi->query($sql);
?>

<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary">Daftar Produk ZULFORCE</h2>
  </div>

  <div class="row g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            <?php if (!empty($row['photo'])): ?>
              <img src="admin/<?= htmlspecialchars($row['photo']) ?>" 
                   class="card-img-top" 
                   style="height:250px; object-fit:cover;" 
                   alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <?php else: ?>
              <img src="no-image.png" 
                   class="card-img-top" 
                   style="height:250px; object-fit:cover;" 
                   alt="No Image">
            <?php endif; ?>

            <div class="card-body text-center">
              <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($row['nama_produk']) ?></h5>
              <p class="text-muted mb-1"><strong>Merk:</strong> <?= htmlspecialchars($row['nama_merk']) ?></p>
              <p class="text-muted mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></p>
              <p class="text-success fw-bold fs-5 mt-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>

              <!-- Deskripsi singkat -->
              <p class="text-muted small mt-2">
                <?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 80))) ?>...
              </p>

              <!-- Tombol Aksi -->
              <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="produk_detail.php?id=<?= $row['id_produk'] ?>" class="btn btn-outline-primary rounded-pill px-3">
                  Detail
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center mt-4">
        <div class="alert alert-warning shadow-sm border-0 rounded-3">
          😢 Tidak ada produk ditemukan untuk kata kunci "<strong><?= htmlspecialchars($cari) ?></strong>"
        </div>
      </div>
    <?php endif; ?>
  </div>

<?php include "footer.php"; ?>
