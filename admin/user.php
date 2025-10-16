<?php
include "../db/koneksi.php";

// query ambil data dari tabel 'pengguna'
$sql = "SELECT * FROM pengguna ORDER BY id_pengguna DESC";
$result = $koneksi->query($sql);

if (!$result) {
    die("Query error: " . $koneksi->error);
}
?>

<div class="content">
  <h3>Data User</h3>

  <!-- Tombol Add User -->
  <a href="?page=user_add" class="btn btn-primary mb-3">+ Add User</a>

  <table class="table table-striped table-bordered mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>No HP</th>
        <th>Alamat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id_pengguna'] ?></td>
        <td><?= $row['nama_pengguna'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['no_hp'] ?></td>
        <td><?= $row['alamat'] ?></td>
        <td>
          <a href="index.php?page=user_update&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i>Edit</a>
          <a href="user_delete.php?id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-danger"  onclick="return confirm('Yakin hapus user ini?')"> <i class="bi bi-trash"></i>Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
