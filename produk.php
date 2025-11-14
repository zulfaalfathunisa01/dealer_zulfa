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

<style>
/* 🌟 Styling untuk kartu produk */
.card-produk {
  border: none;
  border-radius: 18px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  background: #ffffff;
}
.card-produk:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}
.card-produk img {
  height: 220px;
  object-fit: cover;
  border-bottom: 2px solid #f0f0f0;
}
.card-produk .card-body {
  padding: 18px;
}
.card-produk h5 {
  font-weight: 700;
  color: #222;
  margin-bottom: 10px;
}
.card-produk p {
  margin: 3px 0;
}
.card-produk .harga {
  font-size: 1.15rem;
  font-weight: 700;
  color: #008037;
}
.btn-detail {
  border-radius: 30px;
  padding: 6px 18px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}
.btn-detail:hover {
  background-color: #0d6efd;
  color: #fff;
}
</style>

<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary">Daftar Produk <span class="text-dark">ZULFORCE</span></h2>
    <p class="text-muted">Temukan motor impianmu di sini!</p>
  </div>

  <div class="row g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card card-produk h-100">
            <?php if (!empty($row['photo'])): ?>
              <img src="admin/<?= htmlspecialchars($row['photo']) ?>" 
                   alt="<?= htmlspecialchars($row['nama_produk']) ?>" 
                   class="card-img-top">
            <?php else: ?>
              <img src="no-image.png" 
                   alt="No Image" 
                   class="card-img-top">
            <?php endif; ?>

            <div class="card-body text-center">
              <h5><?= htmlspecialchars($row['nama_produk']) ?></h5>
              <p class="text-muted mb-1"><strong>Merk:</strong> <?= htmlspecialchars($row['nama_merk']) ?></p>
              <p class="text-muted mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></p>
              <p class="harga mt-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
              <p class="text-muted small mt-2">
                <?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 70))) ?>...
              </p>
              <a href="produk_detail.php?id=<?= $row['id_produk'] ?>" class="btn btn-outline-primary btn-detail mt-2">
                Lihat Detail
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center mt-4">
        <div class="alert alert-warning shadow-sm border-0 rounded-3">
        Tidak ada produk ditemukan untuk kata kunci "<strong><?= htmlspecialchars($cari) ?></strong>"
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include "footer.php"; ?>
