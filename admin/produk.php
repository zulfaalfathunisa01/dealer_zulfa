<?php
include "../db/koneksi.php";

$sql = "SELECT m.id_produk, m.nama_produk, mk.nama_merk, m.deskripsi, m.harga, m.stock, m.kategori, m.photo
        FROM produk m
        LEFT JOIN merk mk ON m.merk_id = mk.id_merk
        ORDER BY m.id_produk DESC";

$result = $koneksi->query($sql);

// cek error query
if (!$result) {
  die("Query Error: " . $koneksi->error);
}
?>
<div class="content" style="margin-left: 20px; margin-right: 20px;">
  <h2 class="mb-4">Daftar Motor</h2>

  <a href="produk_upload.php" class="btn btn-primary mb-3">+ Tambah Motor</a>

  <div class="table-responsive shadow-sm p-3 bg-white rounded">
    <table class="table table-striped table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Foto</th>
          <th>Nama Produk</th>
          <th>Merk</th>
          <th>Deskripsi</th>
          <th>Stock</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id_produk'] ?></td>
            <td>
              <img src="<?= $row['photo'] ?>" alt="<?= $row['nama_produk'] ?>" width="60"
                style="border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
            </td>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['nama_merk'] ?></td>
            <td><pre><?= nl2br($row['deskripsi']); ?></pre></td>
            <td><?= $row['stock'] ?></td>
            <td><span class="text-dark"><?= ucfirst($row['kategori']) ?></span></td>
            <td><strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong></td>
            <td class="d-flex gap-2 justify-content-center">
              <a href="index.php?page=produk_edit&id=<?= $row['id_produk'] ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <a href="produk_delete.php?id=<?= $row['id_produk'] ?>" 
                 class="btn btn-sm btn-primary" 
                 onclick="return confirm('Yakin hapus produk ini?')">
                <i class="bi bi-trash"></i> Hapus
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
