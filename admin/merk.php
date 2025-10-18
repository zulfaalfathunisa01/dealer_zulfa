<?php
include "../db/koneksi.php";

if (isset($_POST['simpan'])) {
  $nama_merk = $_POST['nama_merk'];
  $sql = "INSERT INTO merk (nama_merk) VALUES ('$nama_merk')";
  if ($koneksi->query($sql)) {
    header("Location:index.php?page=merk");
    exit;
  } else {
    echo "Gagal menambahkan merk: " . $koneksi->error;
  }
}

// Proses hapus merk
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);
  $sql = "DELETE FROM merk WHERE id_merk=$id";
  if ($koneksi->query($sql)) {
    echo "<div class='alert alert-success'>Merk berhasil dihapus!</div>";
  } else {
    echo "<div class='alert alert-danger'>Gagal menghapus merk!</div>";
  }
}

$result = $koneksi->query("SELECT * FROM merk ORDER BY id_merk DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Daftar Merk</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMerk">
    <i class="bi bi-plus"></i> Tambah Merk
  </button>
</div>

<!-- Card hanya untuk tabel -->
<div class="card shadow p-4">
  <table class="table table-bordered table-striped mb-0">
    <thead class="table-dark">
      <tr>
        <th width="60">No</th>
        <th>Nama Merk</th>
        <th width="120">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php $no = 1;
        while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_merk']) ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="index.php?page=merk_update&id=<?= $row['id_merk'] ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="index.php?page=merk&hapus=<?= $row['id_merk'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin hapus merk ini?')">
                   <i class="bi bi-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="3" class="text-center">Belum ada data merk</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah Merk -->
<div class="modal fade" id="tambahMerk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Merk Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Merk</label>
            <input type="text" name="nama_merk" class="form-control" required>
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
