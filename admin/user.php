<?php
include "../db/koneksi.php";

// 🔹 Tambah user baru
if (isset($_POST['simpan'])) {
  $nama = trim($_POST['nama_pengguna']);
  $email = trim($_POST['email']);
  $no_hp = trim($_POST['no_hp']);
  $alamat = trim($_POST['alamat']);

  // 🔍 Cek apakah email sudah digunakan
  $cek = $koneksi->prepare("SELECT * FROM pengguna WHERE email = ?");
  $cek->bind_param("s", $email);
  $cek->execute();
  $hasil = $cek->get_result();

  if ($hasil->num_rows > 0) {
    echo "<script>alert('Email \"$email\" sudah terdaftar!'); window.location='index.php?page=user';</script>";
  } else {
    // ✅ Tambahkan user baru
    $sql = $koneksi->prepare("INSERT INTO pengguna (nama_pengguna, email, no_hp, alamat) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $nama, $email, $no_hp, $alamat);

    if ($sql->execute()) {
      header("Location:index.php?page=user");
      exit;
    } else {
      echo "<div class='alert alert-danger'>Gagal menambahkan user: " . $koneksi->error . "</div>";
    }
  }

  $cek->close();
}

// 🔹 Proses hapus user
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);

  // 🔸 Hapus dulu data yang berkaitan di tabel keranjang (jika ada)
  $koneksi->query("DELETE FROM keranjang WHERE id_pengguna = $id");

  // 🔸 (Opsional) Kalau ada relasi lain seperti tabel transaksi, tambahkan juga:
  // $koneksi->query("DELETE FROM transaksi WHERE id_pengguna = $id");

  // 🔸 Baru hapus user dari tabel pengguna
  $sql = "DELETE FROM pengguna WHERE id_pengguna = $id";
  if ($koneksi->query($sql)) {
    echo "<div class='alert alert-success'>User berhasil dihapus!</div>";
  } else {
    echo "<div class='alert alert-danger'>Gagal menghapus user: {$koneksi->error}</div>";
  }
}


// 🔹 Ambil data user
$result = $koneksi->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC");
?>

<!-- ✨ Style agar alamat tampil rapi -->
<style>
  td.alamat {
    white-space: normal;      /* teks bisa turun ke baris berikutnya */
    word-wrap: break-word;    /* potong otomatis kata panjang */
    max-width: 300px;         /* batasi lebar kolom */
    text-align: left;         /* biar rata kiri, enak dibaca */
    vertical-align: middle;   /* posisi tengah secara vertikal */
  }

  /* Responsif biar tabel bisa discroll di HP */
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

<!-- Card hanya untuk tabel -->
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
              <!-- ✅ Alamat tampil lengkap dan rapi -->
              <td class="alamat"><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <a href="index.php?page=user_update&id=<?= $row['id_pengguna'] ?>"
                    class="btn btn-sm btn-warning d-flex align-items-center justify-content-center"
                    title="Edit User"
                    style="border-radius: 8px; width: 35px; height: 35px;">
                    <i class="bi bi-pencil fs-5"></i>
                  </a>

                  <a href="index.php?page=user&hapus=<?= $row['id_pengguna'] ?>"
                    class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
                    title="Hapus User"
                    style="border-radius: 8px; width: 35px; height: 35px;"
                    onclick="return confirm('Yakin hapus user ini?')">
                    <i class="bi bi-trash fs-5"></i>
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
            <textarea name="alamat" class="form-control" rows="3" required></textarea>
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
