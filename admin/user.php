<?php
include __DIR__ . "/../db/koneksi.php";

// =========================
// 1. Tambah User Baru
// =========================
if (isset($_POST['simpan'])) {

    $nama   = trim($_POST['nama_pengguna']);
    $email  = trim($_POST['email']);
    $no_hp  = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);
    $password = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi_password']);

    // Cek password cocok
    if ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {

        // Cek email sudah dipakai?
        $cek = $koneksi->prepare("SELECT id_pengguna FROM pengguna WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $hasil = $cek->get_result();

        if ($hasil->num_rows > 0) {
            echo "<script>alert('Email \"$email\" sudah terdaftar!');</script>";
        } else {

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Tambah user
            $sql = $koneksi->prepare("
                INSERT INTO pengguna (nama_pengguna, email, no_hp, alamat, password)
                VALUES (?, ?, ?, ?, ?)
            ");

            $sql->bind_param("sssss", $nama, $email, $no_hp, $alamat, $passwordHash);

            if ($sql->execute()) {
                echo "<script>window.location='index.php?page=user';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>Gagal menambahkan user: " . $koneksi->error . "</div>";
            }
        }

        $cek->close();
    }
}

// =========================
// 2. Hapus User
// =========================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    $koneksi->query("DELETE FROM keranjang WHERE id_pengguna = $id");

    $hapus = $koneksi->query("DELETE FROM pengguna WHERE id_pengguna = $id");

    if ($hapus) {
        echo "<script>alert('User berhasil dihapus!'); window.location='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!');</script>";
    }
}

// =========================
// 3. Ambil Data User
// =========================
$result = $koneksi->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC");
?>

<style>
  td.alamat {
    white-space: normal;
    word-wrap: break-word;
    max-width: 300px;
    text-align: left;
    vertical-align: middle;
  }
  .table-responsive {
    overflow-x: auto;
  }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Daftar User</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUser">
    <i class="bi bi-plus"></i> Tambah User
  </button>
</div>

<div class="card shadow p-4">
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle mb-0">
      <thead class="table-dark text-center">
        <tr>
          <th width="60">ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>No HP</th>
          <th>Alamat</th>
          <th width="160">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id_pengguna'] ?></td>
              <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['no_hp']) ?></td>
              <td class="alamat"><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <a href="index.php?page=user_update&id=<?= $row['id_pengguna'] ?>"
                     class="btn btn-sm btn-warning"
                     title="Edit User"
                     style="border-radius: 8px; width: 35px; height: 35px;">
                    <i class="bi bi-pencil fs-5"></i>
                  </a>

                  <a href="index.php?page=user&hapus=<?= $row['id_pengguna'] ?>"
                    onclick="return confirm('Yakin hapus user ini?')"
                    class="btn btn-sm btn-danger"
                    title="Hapus User"
                    style="border-radius: 8px; width: 35px; height: 35px;">
                    <i class="bi bi-trash fs-5"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">Belum ada data user</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- =========================
     MODAL TAMBAH USER (FINAL)
     ========================= -->
<div class="modal fade" id="tambahUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Tambah User Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_pengguna" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required></textarea>
          </div>

          <!-- =========================
               TAMBAHAN PASSWORD
               ========================= -->
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="konfirmasi_password" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>

      </form>
    </div>
  </div>
</div>
