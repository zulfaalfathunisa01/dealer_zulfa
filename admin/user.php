<?php
include "../db/koneksi.php";

// query ambil data dari tabel 'pengguna'
$sql = "SELECT * FROM pengguna ORDER BY id_pengguna DESC";
$result = $koneksi->query($sql);

if (!$result) {
    die("Query error: " . $koneksi->error);
}
?>

<div class="content mt-4" style="margin-left: 80px; margin-right: 80px;">
  <h3 class="mb-4">Data User</h3>

  <!-- Tombol Add User -->
  <a href="?page=user_add" class="btn btn-primary mb-3">+ Add User</a>

  <div class="table-responsive shadow-sm p-4 bg-white rounded">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th width="5%">ID</th>
          <th width="20%">Nama</th>
          <th width="20%">Email</th>
          <th width="15%">No HP</th>
          <th width="25%">Alamat</th>
          <th width="15%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id_pengguna'] ?></td>
          <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['no_hp']) ?></td>
          <td><?= htmlspecialchars($row['alamat']) ?></td>
          <td class="text-center">
            <a href="index.php?page=user_update&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="user_delete.php?id=<?= $row['id_pengguna'] ?>" 
               class="btn btn-sm btn-danger"  
               onclick="return confirm('Yakin hapus user ini?')">
              <i class="bi bi-trash"></i> Hapus
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
