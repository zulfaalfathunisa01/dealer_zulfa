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
  <div class="card shadow-sm p-4" style="border-radius: 16px; max-width: 500px; margin: 20px auto; background: #ffffff;">
    
    <!-- Avatar & Nama -->
    <div style="text-align:center; margin-bottom:20px;">
      <img src="avatar.png" alt="Avatar" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:2px solid #007bff;">
      <h2 style="margin-top:10px; color:#007bff;"><?= htmlspecialchars($data_profil['nama_pengguna'] ?? 'Tidak diketahui') ?></h2>
    </div>

    <!-- Info Profil -->
    <div id="profil-view">
      <div style="display:flex; flex-direction:column; gap:12px;">
        <div><strong>Email:</strong> <?= htmlspecialchars($data_profil['email'] ?? '-') ?></div>
        <div><strong>Telepon:</strong> <?= htmlspecialchars($data_profil['no_hp'] ?? '-') ?></div>
        <div><strong>Alamat:</strong> <?= htmlspecialchars($data_profil['alamat'] ?? '-') ?></div>
      </div>

      <div style="text-align:center; margin-top:20px;">
        <button id="editBtn" style="background:#007bff; color:#fff; padding:10px 20px; border:none; border-radius:8px; font-weight:bold;">✏️ Edit Profil</button>
      </div>
    </div>

    <!-- Form Edit (disembunyikan dulu) -->
    <form id="profil-edit" action="" method="post" style="display:none;">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama_pengguna" value="<?= htmlspecialchars($data_profil['nama_pengguna'] ?? '') ?>" required class="form-control">
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data_profil['email'] ?? '') ?>" required class="form-control">
      </div>
      <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="no_hp" value="<?= htmlspecialchars($data_profil['no_hp'] ?? '') ?>" required class="form-control">
      </div>
      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" rows="3" class="form-control"><?= htmlspecialchars($data_profil['alamat'] ?? '') ?></textarea>
      </div>
      <div style="text-align:center;">
        <button type="submit" name="simpan" style="background:#007bff; color:white; border:none; padding:10px 20px; border-radius:8px;">💾 Simpan</button>
        <button type="button" id="batalBtn" style="background:#ccc; color:#000; border:none; padding:10px 20px; border-radius:8px;">❌ Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById("editBtn").addEventListener("click", function() {
  document.getElementById("profil-view").style.display = "none";
  document.getElementById("profil-edit").style.display = "block";
});

document.getElementById("batalBtn").addEventListener("click", function() {
  document.getElementById("profil-edit").style.display = "none";
  document.getElementById("profil-view").style.display = "block";
});
</script>



      <!-- TAB WISHLIST -->
<?php
include "db/koneksi.php";

// Inisialisasi wishlist di session
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Tambah ke wishlist
if (isset($_GET['id'])) {
    $id_produk = intval($_GET['id']);
    if (!in_array($id_produk, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $id_produk;
    }
    header("Location: wishlist.php");
    exit;
}

// Hapus dari wishlist
if (isset($_GET['hapus'])) {
    $id_produk = intval($_GET['hapus']);
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$id_produk]);
    header("Location: wishlist.php");
    exit;
}

// Ambil data produk dari database
$wishlist_produk = false;
if (!empty($_SESSION['wishlist'])) {
    $ids = implode(",", array_map('intval', $_SESSION['wishlist']));
    $sql = "SELECT * FROM produk WHERE id_produk IN ($ids)";
    $wishlist_produk = $koneksi->query($sql);
}
?>

<div class="tab-pane fade" id="wishlist" role="tabpanel">
  <div class="scroll-area">
    <?php if ($wishlist_produk && $wishlist_produk->num_rows > 0): ?>
      <?php while ($row = $wishlist_produk->fetch_assoc()): ?>
        <div class="wishlist-item">
          <img src="admin/<?= htmlspecialchars($row['photo']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
          <div class="wishlist-info">
            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
            <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
          </div>
          <div class="wishlist-buttons">
            <a href="produk_keranjang.php?id=<?= $row['id_produk'] ?>" class="btn btn-keranjang">+ Keranjang</a>
            <a href="checkout.php?id=<?= $row['id_produk'] ?>" class="btn btn-keranjang">Checkout</a>
            <a href="wishlist.php?hapus=<?= $row['id_produk'] ?>" class="btn btn-hapus" onclick="return confirm('Hapus dari wishlist?')">Hapus</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted mt-3">Belum ada produk di wishlist 💔</p>
    <?php endif; ?>
  </div>
</div>

<style>
.scroll-area {
  max-height: 400px; /* sesuaikan dengan tinggi tab */
  overflow-y: auto;
  padding: 10px 0;
}

.wishlist-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 15px;
  margin-bottom: 15px;
  transition: transform 0.2s ease;
}

.wishlist-item:hover {
  transform: scale(1.01);
}

.wishlist-item img {
  width: 140px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
}

.wishlist-info {
  flex: 1;
  margin-left: 20px;
}

.wishlist-info h4 {
  margin: 0 0 5px 0;
  color: #007bff;
  font-size: 18px;
}

.wishlist-info p {
  margin: 0;
  color: green;
  font-weight: bold;
  font-size: 16px;
}

.wishlist-buttons {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.btn {
  text-decoration: none;
  color: #fff;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
  transition: background 0.2s ease;
}

.btn-keranjang {
  background: #28a745;
}
.btn-keranjang:hover { background: #218838; }

.btn-hapus {
  background: #dc3545;
}
.btn-hapus:hover { background: #c82333; }
</style>


  <!-- TAB RIWAYAT -->
   <?php
include "db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['id_pengguna'])) {
  echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil riwayat transaksi pengguna
$query_riwayat = $koneksi->query("
  SELECT id_transaksi, tanggal_transaksi, total_harga, status
  FROM transaksi
  WHERE pengguna_id = '$id_pengguna'
  ORDER BY tanggal_transaksi DESC
");
?>

<div class="tab-pane fade" id="riwayat" role="tabpanel">
  <div class="scroll-area">
    <?php if ($query_riwayat && $query_riwayat->num_rows > 0): ?>
      <?php while ($row = $query_riwayat->fetch_assoc()): ?>
        <div class="order-item">
          <div class="order-header">
            <span><b>ID:</b> <?= $row['id_transaksi'] ?></span>
            <span><?= date('d-m-Y H:i', strtotime($row['tanggal_transaksi'])) ?> WIB</span>
          </div>
          <div class="order-body d-flex justify-content-between align-items-center">
            <div>
              <p><b>Total:</b> Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></p>
              <p>
                <b>Status:</b>
                <?php
                  switch($row['status']){
                    case 'proses': echo '<span class="badge bg-warning">Proses</span>'; break;
                    case 'kirim': echo '<span class="badge bg-info">Dikirim</span>'; break;
                    case 'selesai': echo '<span class="badge bg-success">Selesai</span>'; break;
                    default: echo '<span class="badge bg-danger">Batal</span>';
                  }
                ?>
              </p>
            </div>
            <a href="riwayat_detail.php?id=<?= $row['id_transaksi'] ?>" class="btn-detail">Lihat Detail</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted mt-3">Belum ada transaksi 💔</p>
    <?php endif; ?>
  </div>
</div>

<style>
.scroll-area {
  max-height: 400px;
  overflow-y: auto;
  padding: 10px 0;
}

.order-item {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 15px;
  margin-bottom: 15px;
  transition: transform 0.2s ease;
}

.order-item:hover {
  transform: scale(1.01);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 8px;
  margin-bottom: 10px;
}

.badge {
  padding: 6px 10px;
  border-radius: 6px;
  color: #fff;
  font-size: 13px;
}
.bg-warning { background: #ffc107; color: #000; }
.bg-info { background: #0dcaf0; color: #000; }
.bg-success { background: #28a745; }
.bg-danger { background: #dc3545; }

.btn-detail {
  background: #007bff;
  color: white;
  text-decoration: none;
  padding: 8px 14px;
  border-radius: 6px;
  font-size: 14px;
}
.btn-detail:hover {
  background: #0056b3;
}
</style>


        <!-- isi -->

        
      </div>
    </div>

  </div>
</div>

<?php include "footer.php"; ?>