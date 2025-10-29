<?php
include "../db/koneksi.php";

$sql = "SELECT m.id_produk, m.nama_produk, mk.nama_merk, m.deskripsi, m.harga, m.stock, m.kategori, m.photo
        FROM produk m
        LEFT JOIN merk mk ON m.merk_id = mk.id_merk
        ORDER BY m.id_produk DESC";

$result = $koneksi->query($sql);

if (!$result) {
  die("Query Error: " . $koneksi->error);
}
?>

<div class="content" style="margin: 20px;">
  <h2 class="mb-4 text-primary fw-bold">Daftar Motor</h2>

  <a href="produk_upload.php" class="btn btn-primary mb-3">+ Tambah Motor</a>

  <div class="table-responsive shadow-sm p-3 bg-white rounded">
    <table class="table table-hover align-middle text-center" style="font-size: 14px;">
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
            <td style="max-width: 200px; text-align:left;">
              <pre style="white-space: pre-wrap; margin:0;"><?= htmlspecialchars($row['deskripsi']) ?></pre>
            </td>
            <td><?= $row['stock'] ?></td>
            <td><?= ucfirst($row['kategori']) ?></td>
            <td><strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong></td>
            <td>
              <div class="d-flex justify-content-center gap-1">
               <a href="index.php?page=produk_edit&id=<?= $row['id_produk'] ?>"
    class="btn btn-sm btn-warning d-flex align-items-center justify-content-center"
    title="Edit Produk"
    style="border-radius: 8px; width: 35px; height: 35px;">
    <i class="bi bi-pencil-square fs-5"></i>
  </a>

  <a href="produk_delete.php?id=<?= $row['id_produk'] ?>"
    class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
    title="Hapus Produk"
    style="border-radius: 8px; width: 35px; height: 35px;"
    onclick="return confirm('Yakin hapus produk ini?')">
    <i class="bi bi-trash3 fs-5"></i>
  </a>
  </div>
  </td>
  </tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>