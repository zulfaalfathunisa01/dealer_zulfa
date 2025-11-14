<?php
session_start();
include "db/koneksi.php";
include "header.php";

$id_pengguna = $_SESSION['id_pengguna'];

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
  echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
  exit;
}

// Ambil data profil
$query_profil = $koneksi->query("SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
$data_profil = $query_profil->fetch_assoc();

// Ambil inisial nama (misal "Zulfa Alfathunisa" → "ZA")
function getInisial($nama)
{
  $nama = trim($nama);
  $parts = explode(" ", $nama);
  $inisial = strtoupper(substr($parts[0], 0, 1));
  if (count($parts) > 1) $inisial .= strtoupper(substr(end($parts), 0, 1));
  return $inisial;
}
$inisial = getInisial($data_profil['nama_pengguna'] ?? 'U');

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
      <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist" data-bs-target="#wishlist" type="button" role="tab">❤️ Wishlist</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">📜 Riwayat</button>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">

    <!-- TAB PROFIL -->
    <div class="tab-pane fade show active" id="profil" role="tabpanel">
      <div class="card shadow-sm p-4" style="border-radius:16px; max-width:500px; margin:20px auto; background:#ffffff;">

        <!-- Avatar & Nama -->
        <div style="text-align:center; margin-bottom:20px;">
          <?php if (!empty($data_profil['foto'])): ?>
            <img src="uploads/<?= htmlspecialchars($data_profil['foto']) ?>" alt="Avatar"
              style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:2px solid #007bff;">
          <?php else: ?>
            <div style="
          width:100px; height:100px; border-radius:50%;
          background:#007bff; color:white; font-size:36px;
          display:flex; align-items:center; justify-content:center;
          margin:0 auto; font-weight:bold;">
              <?= $inisial ?>
            </div>
          <?php endif; ?>
          <h2 style="margin-top:10px; color:#007bff;">
            <?= htmlspecialchars($data_profil['nama_pengguna'] ?? 'Tidak diketahui') ?>
          </h2>
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

        <!-- Form Edit -->
        <?php
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }

        // Pastikan user sudah login
        if (!isset($_SESSION['id_pengguna'])) {
          echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
          exit;
        }

        include "db/koneksi.php";

        $id_pengguna = $_SESSION['id_pengguna'];

        // Ambil data profil user
        $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'");
        $data_profil = mysqli_fetch_assoc($query);

        // Jika tombol simpan ditekan
        if (isset($_POST['simpan'])) {
          $nama_pengguna = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
          $email = mysqli_real_escape_string($koneksi, $_POST['email']);
          $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
          $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

          // ====== AMBIL PASSWORD INPUT ======
          $password_lama = $_POST['password_lama'] ?? '';
          $password_baru = $_POST['password_baru'] ?? '';
          $konfirmasi = $_POST['konfirmasi_password'] ?? '';

          // ====== HANDLE UPLOAD FOTO ======
          $foto_baru = $_FILES['foto']['name'];
          if (!empty($foto_baru)) {
            $tmp = $_FILES['foto']['tmp_name'];
            $nama_file = time() . '_' . basename($foto_baru);
            if (move_uploaded_file($tmp, "uploads/" . $nama_file)) {
              if (!empty($data_profil['foto']) && file_exists("uploads/" . $data_profil['foto'])) {
                unlink("uploads/" . $data_profil['foto']);
              }
              $foto_update = "foto='$nama_file',";
            }
          } else {
            $foto_update = "";
          }

          // ================================
          // 🚨 BAGIAN UBAH PASSWORD
          // ================================
          if (!empty($password_lama) || !empty($password_baru) || !empty($konfirmasi)) {

            // Semua harus diisi
            if (empty($password_lama) || empty($password_baru) || empty($konfirmasi)) {
              echo "<script>alert('Semua kolom password harus diisi!');</script>";
              exit;
            }

            // Cek password lama benar
            if (!password_verify($password_lama, $data_profil['password'])) {
              echo "<script>alert('Password lama salah!');</script>";
              exit;
            }

            // Password baru harus sama
            if ($password_baru !== $konfirmasi) {
              echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
              exit;
            }

            // Hash password baru
            $password_baru_hashed = password_hash($password_baru, PASSWORD_DEFAULT);
            $password_update = "password='$password_baru_hashed',";
          } else {
            $password_update = "";
          }

          // ================================
          // UPDATE DATA
          // ================================
          $update = mysqli_query($koneksi, "
      UPDATE pengguna SET 
        nama_pengguna='$nama_pengguna',
        email='$email',
        no_hp='$no_hp',
        alamat='$alamat',
        $foto_update
        $password_update
        id_pengguna='$id_pengguna'
      WHERE id_pengguna='$id_pengguna'
  ");

          if ($update) {
            echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
          } else {
            echo "<script>alert('Gagal memperbarui profil!');</script>";
          }
        }
        ?>

        <form id="profil-edit" action="" method="post" style="display:none;" enctype="multipart/form-data">
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
          <div class="mb-3">
            <label>Foto Profil</label>
            <input type="file" name="foto" class="form-control">
          </div>

          <h5 class="mt-4">Ubah Password</h5>

          <div class="mb-3">
            <label>Password Lama</label>
            <div class="input-group">
              <input type="password" name="password_lama" id="password_lama" class="form-control">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_lama', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label>Password Baru</label>
            <div class="input-group">
              <input type="password" name="password_baru" id="password_baru" class="form-control">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_baru', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <div class="input-group">
              <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('konfirmasi_password', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>



          <div style="text-align:center;">
            <button type="submit" name="simpan" style="background:#007bff; color:white; border:none; padding:10px 20px; border-radius:8px;">💾 Simpan</button>
            <button type="button" id="batalBtn" style="background:#ccc; color:#000; border:none; padding:10px 20px; border-radius:8px;">❌ Batal</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector("i");

        if (input.type === "password") {
          input.type = "text";
          icon.classList.remove("bi-eye");
          icon.classList.add("bi-eye-slash");
        } else {
          input.type = "password";
          icon.classList.remove("bi-eye-slash");
          icon.classList.add("bi-eye");
        }
      }
    
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
    <div class="tab-pane fade" id="wishlist" role="tabpanel">
      <?php
      include "db/koneksi.php";
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }

      // Pastikan user login
      if (!isset($_SESSION['id_pengguna'])) {
        echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
        exit;
      }

      $id_pengguna = $_SESSION['id_pengguna'];

      // =====================================================
      // 🔹 HAPUS PRODUK DARI WISHLIST
      // =====================================================
      if (isset($_GET['hapus'])) {
        $id_wishlist = intval($_GET['hapus']);
        $hapus = $koneksi->query("DELETE FROM wishlist WHERE id_wishlist = $id_wishlist AND id_pengguna = $id_pengguna");

        if ($hapus) {
          echo "<script>
              alert('Produk berhasil dihapus dari wishlist!');
              window.location='profil.php#wishlist';
            </script>";
          exit;
        } else {
          echo "<script>alert('Gagal menghapus wishlist!');</script>";
        }
      }

      // =====================================================
      // 🔹 TAMBAH PRODUK KE WISHLIST
      // =====================================================
      if (isset($_GET['id'])) {
        $id_produk = intval($_GET['id']);
        $cek = $koneksi->query("SELECT * FROM wishlist WHERE id_pengguna = $id_pengguna AND id_produk = $id_produk");

        if ($cek->num_rows > 0) {
          echo "<script>alert('Produk ini sudah ada di wishlist kamu!'); window.location='profil.php#wishlist';</script>";
        } else {
          $sql = "INSERT INTO wishlist (id_pengguna, id_produk, tanggal_ditambahkan) VALUES ($id_pengguna, $id_produk, NOW())";
          if ($koneksi->query($sql)) {
            echo "<script>alert('Produk berhasil ditambahkan ke wishlist!'); window.location='profil.php#wishlist';</script>";
          } else {
            echo "<script>alert('Gagal menambahkan produk ke wishlist.'); window.location='profil.php#wishlist';</script>";
          }
        }
      }

      // =====================================================
      // 🔹 AMBIL DATA WISHLIST USER
      // =====================================================
      $wishlist = $koneksi->query("
    SELECT w.id_wishlist, p.id_produk, p.nama_produk, p.harga, p.photo, w.tanggal_ditambahkan 
    FROM wishlist w
    JOIN produk p ON w.id_produk = p.id_produk
    WHERE w.id_pengguna = $id_pengguna
    ORDER BY w.tanggal_ditambahkan DESC
  ");
      ?>

      <style>
        .wishlist-container {
          padding: 30px 10px;
          background-color: #f8faff;
          border-radius: 10px;
          min-height: 400px;
        }

        .wishlist-title {
          text-align: center;
          font-size: 1.7rem;
          color: #007bff;
          font-weight: 700;
          margin-bottom: 25px;
        }

        .wishlist-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
          gap: 20px;
        }

        .wishlist-card {
          background: #fff;
          border-radius: 12px;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
          overflow: hidden;
          transition: all 0.25s ease;
          display: flex;
          flex-direction: column;
          justify-content: space-between;
        }

        .wishlist-card:hover {
          transform: scale(1.02);
          box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .wishlist-card img {
          width: 100%;
          height: 180px;
          object-fit: cover;
        }

        .wishlist-card-body {
          padding: 15px;
        }

        .wishlist-card-body h5 {
          color: #007bff;
          font-size: 18px;
          margin-bottom: 8px;
        }

        .wishlist-card-body p {
          color: green;
          font-weight: bold;
          font-size: 16px;
          margin: 0;
        }

        .wishlist-buttons {
          display: flex;
          justify-content: space-around;
          padding: 12px 10px;
          background-color: #f1f5fb;
        }

        .wishlist-buttons a {
          text-decoration: none;
          color: #fff;
          padding: 7px 12px;
          border-radius: 6px;
          font-size: 13px;
          transition: background 0.2s ease;
          font-weight: 600;
        }

        .btn-keranjang {
          background: #28a745;
        }

        .btn-keranjang:hover {
          background: #218838;
        }

        .btn-checkout {
          background: #007bff;
        }

        .btn-checkout:hover {
          background: #0062cc;
        }

        .btn-hapus {
          background: #dc3545;
        }

        .btn-hapus:hover {
          background: #c82333;
        }

        .empty-wishlist {
          text-align: center;
          color: #777;
          font-size: 16px;
          margin-top: 40px;
        }
      </style>

      <div class="wishlist-container">
        <h3 class="wishlist-title">Wishlist Kamu</h3>

        <?php if ($wishlist->num_rows > 0): ?>
          <div class="wishlist-grid">
            <?php while ($row = $wishlist->fetch_assoc()): ?>
              <div class="wishlist-card">
                <img src="admin/<?= htmlspecialchars($row['photo']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                <div class="wishlist-card-body">
                  <h5><?= htmlspecialchars($row['nama_produk']) ?></h5>
                  <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                </div>
                <div class="wishlist-buttons">
                  <form action="produk_keranjang.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                    <button type="submit" class="btn btn-keranjang" name="tambah_keranjang">Keranjang</button>
                  </form>
                  <a href="checkout.php?id=<?= $row['id_produk'] ?>" class="btn-checkout">Checkout</a>
                  <a href="profil.php?hapus=<?= $row['id_wishlist'] ?>#wishlist"
                    class="btn-hapus"
                    onclick="return confirm('Yakin ingin menghapus dari wishlist?')">
                    Hapus
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="empty-wishlist">Belum ada produk di wishlist kamu</p>
        <?php endif; ?>
      </div>
    </div>



    <!-- TAB RIWAYAT -->
    <?php
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
      <div class="accordion" id="accordionRiwayat">
        <?php if ($query_riwayat && $query_riwayat->num_rows > 0): ?>
          <?php while ($row = $query_riwayat->fetch_assoc()):
            $trans_id = $row['id_transaksi'];
            $collapse_id = "collapse$trans_id";
          ?>
            <div class="accordion-item mb-2">
              <h2 class="accordion-header" id="heading<?= $trans_id ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>" aria-expanded="false" aria-controls="<?= $collapse_id ?>">
                  ID: <?= $trans_id ?> | <?= date('d-m-Y H:i', strtotime($row['tanggal_transaksi'])) ?> WIB | Total: Rp <?= number_format($row['total_harga'], 0, ',', '.') ?> |
                  Status:
                  <?php
                  switch ($row['status']) {
                    case 'proses':
                      echo '<span class="badge bg-warning">Proses</span>';
                      break;
                    case 'kirim':
                      echo '<span class="badge bg-info">Dikirim</span>';
                      break;
                    case 'selesai':
                      echo '<span class="badge bg-success">Selesai</span>';
                      break;
                    default:
                      echo '<span class="badge bg-danger">Batal</span>';
                  }
                  ?>
                </button>
              </h2>
              <div id="<?= $collapse_id ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $trans_id ?>" data-bs-parent="#accordionRiwayat">
                <div class="accordion-body">
                  <?php
                  // Ambil detail transaksi
                  $query_detail = $koneksi->query("
                SELECT p.nama_produk, td.jumlah, td.harga
                FROM transaksi_detail td
                JOIN produk p ON td.produk_id = p.id_produk
                WHERE td.transaksi_id = '$trans_id'
              ");
                  if ($query_detail->num_rows > 0):
                  ?>
                    <table class="table table-bordered mb-0">
                      <thead>
                        <tr>
                          <th>Produk</th>
                          <th>Jumlah</th>
                          <th>Harga</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($d = $query_detail->fetch_assoc()): ?>
                          <tr>
                            <td><?= htmlspecialchars($d['nama_produk']) ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['jumlah'] * $d['harga'], 0, ',', '.') ?></td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  <?php else: ?>
                    <p class="text-muted">Tidak ada detail transaksi.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-muted mt-3">Belum ada transaksi</p>
        <?php endif; ?>
      </div>
    </div>

    <style>
      .accordion-button {
        font-weight: 500;
      }

      .badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 13px;
      }

      .bg-warning {
        background: #ffc107;
        color: #000;
      }

      .bg-info {
        background: #0dcaf0;
        color: #000;
      }

      .bg-success {
        background: #28a745;
      }

      .bg-danger {
        background: #dc3545;
      }

      .table {
        margin-bottom: 0;
      }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- isi -->


  </div>
</div>

</div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Cek apakah URL mengandung #wishlist
    if (window.location.hash === "#wishlist") {
      var tabTrigger = document.querySelector('a[href="#wishlist"]');
      if (tabTrigger) {
        var tab = new bootstrap.Tab(tabTrigger);
        tab.show(); // aktifkan tab wishlist
      }
    }
  });
</script>


<?php include "footer.php"; ?>