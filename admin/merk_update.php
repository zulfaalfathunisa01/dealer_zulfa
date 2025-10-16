<?php
include "../db/koneksi.php";

// Cek parameter id
if (!isset($_GET['id'])) {
    die("ID merk tidak ditemukan!");
}

$id = intval($_GET['id']); // pastikan angka

// Ambil data merk berdasarkan ID
$result = $koneksi->query("SELECT * FROM merk WHERE id_merk = $id");
if ($result->num_rows == 0) {
    die("Merk tidak ditemukan!");
}
$merk = $result->fetch_assoc();

// Proses update
if (isset($_POST['update'])) {
    $nama_merk = mysqli_real_escape_string($koneksi, $_POST['nama_merk']);

    $sql = "UPDATE merk SET nama_merk='$nama_merk' WHERE id_merk=$id";
    if ($koneksi->query($sql)) {
        // Redirect dengan status sukses
        header('location:index.php?page=merk');
        exit;
    } else {
        $error = "❌ Gagal update merk: " . $koneksi->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Merk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:600px;">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">Edit Merk</h5>
    </div>
    <div class="card-body">

      <!-- Tampilkan error jika ada -->
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nama Merk</label>
          <input type="text" name="nama_merk" class="form-control"
                 value="<?= htmlspecialchars($merk['nama_merk']) ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
