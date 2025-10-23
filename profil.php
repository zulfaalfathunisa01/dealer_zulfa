<?php
session_start();
include "db/koneksi.php";
include "header.php";

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data profil
$query_profil = $koneksi->query("SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
$data_profil = $query_profil->fetch_assoc();

// Ambil wishlist
// $query_wishlist = $koneksi->query("
//   SELECT p.nama_produk, p.harga, p.gambar 
//   FROM wishlist w 
//   JOIN produk p ON w.id_produk = p.id_produk 
//   WHERE w.id_pengguna = '$id_pengguna'
// ");

// Ambil riwayat transaksi
// $query_riwayat = $koneksi->query("
//   SELECT t.id_transaksi, t.tanggal_transaksi, t.total_harga, t.status 
//   FROM transaksi t 
//   WHERE t.id_pengguna = '$id_pengguna'
// ");
?>

<div class="container mt-5">
  <h2 class="mb-4 text-center">Profil Pengguna</h2>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab">👤 Profil</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab">❤️ Wishlist</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">📜 Riwayat</button>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">

    <!-- TAB PROFIL -->
    <div class="tab-pane fade show active" id="profil" role="tabpanel">
      <div class="card shadow-sm p-4">
        <h4><?= htmlspecialchars($data_profil['nama'] ?? 'Tidak diketahui') ?></h4>
        <p><strong>Email:</strong> <?= htmlspecialchars($data_profil['email'] ?? '-') ?></p>
        <p><strong>Telepon:</strong> <?= htmlspecialchars($data_profil['telepon'] ?? '-') ?></p>
        <p><strong>Tanggal Daftar:</strong>
          <?= isset($data_profil['tanggal_daftar']) ? date('d F Y', strtotime($data_profil['tanggal_daftar'])) : '-' ?>
        </p>
      </div>
    </div>

    <!-- TAB WISHLIST -->
    <div class="tab-pane fade" id="wishlist" role="tabpanel">
      <div class="row">
        <?php if ($query_wishlist->num_rows > 0): ?>
          <?php while ($w = $query_wishlist->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
              <div class="card shadow-sm">
                <img src="upload/<?= $w['gambar'] ?>" class="card-img-top" alt="<?= htmlspecialchars($w['nama_produk']) ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($w['nama_produk']) ?></h5>
                  <p class="card-text">Rp<?= number_format($w['harga'], 0, ',', '.') ?></p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-muted">Belum ada wishlist.</p>
        <?php endif; ?>
      </div>
    </div>

    <?php
$id_pengguna = $_SESSION['id_pengguna'];

$query_riwayat = mysqli_query($koneksi, "
  SELECT id_transaksi, tanggal_transaksi, total_harga, status
  FROM transaksi
  WHERE pengguna_id = '$id_pengguna'
  ORDER BY tanggal_transaksi DESC
");
?>

<div class="tab-pane fade" id="riwayat" role="tabpanel">
  <h4>📜 Riwayat Transaksi</h4>

  <?php if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0): ?>
    <table class="table table-striped mt-3">
      <thead>
        <tr>
          <th>ID Transaksi</th>
          <th>Tanggal</th>
          <th>Total Harga</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($query_riwayat)): ?>
          <tr>
            <td><?= $row['id_transaksi'] ?></td>
            <td><?= $row['tanggal_transaksi'] ?></td>
            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td><?= ucfirst($row['status']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted mt-3">Belum ada riwayat transaksi.</p>
  <?php endif; ?>
</div>

        <!-- isi -->
      </div>
    </div>

  </div>
</div>

<?php include "footer.php"; ?>