<?php
session_start();
include "db/koneksi.php";
include "header.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data transaksi milik user ini
$sql = "
    SELECT t.id_transaksi, t.tanggal_transaksi, t.total_harga, t.status
    FROM transaksi t
    WHERE t.pengguna_id = '$id_pengguna'
    ORDER BY t.tanggal_transaksi DESC
";
$result = $koneksi->query($sql);
?>

<div class="container mt-5">
  <h2 class="text-center text-primary mb-4">📦 Riwayat Pesanan Saya</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-primary">
          <tr>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Detail</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id_transaksi'] ?></td>
              <td><?= $row['tanggal_transaksi'] ?></td>
              <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
              <td>
                <span class="badge bg-<?= 
                  $row['status'] == 'selesai' ? 'success' :
                  ($row['status'] == 'proses' ? 'primary' :
                  ($row['status'] == 'pending' ? 'warning' : 'danger'))
                ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </td>
              <td><a href="transaksi_detail.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-sm btn-outline-primary">Lihat</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada pesanan yang dilakukan 😢</div>
  <?php endif; ?>
</div>

<?php include "footer.php"; ?>
