<?php
include "../db/koneksi.php";

// Tambah user baru
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama_pengguna'];
  $email = $_POST['email'];
  $no_hp = $_POST['no_hp'];
  $alamat = $_POST['alamat'];

  $sql = "INSERT INTO pengguna (nama_pengguna, email, no_hp, alamat) 
          VALUES ('$nama', '$email', '$no_hp', '$alamat')";
  if ($koneksi->query($sql)) {
    header("Location:index.php?page=user");
    exit;
  } else {
    echo "<div class='alert alert-danger'>Gagal menambahkan user: " . $koneksi->error . "</div>";
  }
}

// Proses hapus user
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);
  $sql = "DELETE FROM pengguna WHERE id_pengguna=$id";
  if ($koneksi->query($sql)) {
    echo "<div class='alert alert-success'>User berhasil dihapus!</div>";
  } else {
    echo "<div class='alert alert-danger'>Gagal menghapus user!</div>";
  }
}

// Ambil data user
$result = $koneksi->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Daftar User</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUser">
    <i class="bi bi-plus"></i> Tambah User
  </button>
</div>

<!-- Card hanya untuk tabel -->
<div class="card shadow p-4">
  <table class="table table-bordered table-striped mb-0">
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
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="index.php?page=user_update&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="index.php?page=user&hapus=<?= $row['id_pengguna'] ?>" 
                   class="btn btn-sm btn-danger" 
                   onclick="return confirm('Yakin hapus user ini?')">
                   <i class="bi bi-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center">Belum ada data user</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Tambah User Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Pengguna</label>
            <input type="text" name="nama_pengguna" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required></textarea>
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
