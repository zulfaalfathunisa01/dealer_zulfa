<?php
include "../db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');


// UBAH STATUS TRANSAKSI
if (isset($_GET['ubah_status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = $koneksi->real_escape_string($_GET['ubah_status']);

    $allowedStatus = ['pesanan dibuat', 'proses', 'kirim', 'selesai', 'batal'];

    if (in_array($status, $allowedStatus)) {
        mysqli_query($koneksi, "UPDATE transaksi SET status='$status' WHERE id_transaksi=$id");
    }

    echo "<script>window.location='index.php?page=transaksi';</script>";
    exit;
}
?>

<div class="container-fluid">
  <h2 class="fw-bold text-primary mb-4">
    <i class="bi bi-cart-check"></i> Kelola Transaksi
  </h2>

  <!-- Pencarian -->
  <div class="card card-custom p-3 mb-4 bg-white border-0">
    <form method="GET" class="d-flex align-items-center">
      <input type="hidden" name="page" value="transaksi">
      <input type="text" name="search" class="form-control me-2"
             placeholder="Cari ID / Nomor Booking / Status / Nama..."
             value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="card card-custom p-3 bg-white border-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center mb-0">
        <thead class="text-white" style="background-color:#0d6efd;">
          <tr>
            <th>ID</th>
            <th>Nomor Booking</th>
            <th>Nama Pengguna</th>
            <th>Total Harga</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
            <th>Struk</th>
          </tr>
        </thead>
        <tbody>

<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';

$q = "
  SELECT t.*, p.nama_pengguna 
  FROM transaksi t
  JOIN pengguna p ON t.pengguna_id = p.id_pengguna
  WHERE t.id_transaksi LIKE '%$search%' 
     OR t.nomor_booking LIKE '%$search%'
     OR t.status LIKE '%$search%' 
     OR p.nama_pengguna LIKE '%$search%'
  ORDER BY t.id_transaksi DESC
";

$result = mysqli_query($koneksi, $q);

$badgeClass = [
  'pesanan dibuat' => 'secondary',
  'proses' => 'primary',
  'kirim' => 'info text-dark',
  'selesai' => 'success',
  'batal' => 'danger'
];

if (mysqli_num_rows($result) > 0):
  while ($row = mysqli_fetch_assoc($result)):
    $status = strtolower($row['status']);
    $class = $badgeClass[$status] ?? 'secondary';
?>

<tr>
  <td><?= $row['id_transaksi']; ?></td>
  <td><strong><?= $row['nomor_booking']; ?></strong></td>
  <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
  <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
  <td><?= date('d F Y, H:i', strtotime($row['tanggal_transaksi'])); ?> WIB</td>

  <td>
    <span class="badge bg-<?= $class ?>"><?= ucfirst($status) ?></span>
  </td>

  <td>
    <?php if (in_array($status, ['pesanan dibuat', 'proses', 'kirim'])): ?>

      <?php if ($status == 'pesanan dibuat'): ?>
        <a href="index.php?page=transaksi&ubah_status=proses&id=<?= $row['id_transaksi']; ?>"
           class="btn btn-sm btn-primary me-1">Proses</a>

      <?php elseif ($status == 'proses'): ?>
        <a href="index.php?page=transaksi&ubah_status=kirim&id=<?= $row['id_transaksi']; ?>"
           class="btn btn-sm btn-info text-white me-1">Kirim</a>

      <?php elseif ($status == 'kirim'): ?>
        <a href="index.php?page=transaksi&ubah_status=selesai&id=<?= $row['id_transaksi']; ?>"
           class="btn btn-sm btn-success me-1">Selesai</a>
      <?php endif; ?>

      <!-- Tombol Batal -->
      <a href="#" class="btn btn-sm btn-danger"
         data-bs-toggle="modal"
         data-bs-target="#modalBatal<?= $row['id_transaksi']; ?>">Batal</a>

      <!-- Modal Batal -->
      <div class="modal fade" id="modalBatal<?= $row['id_transaksi']; ?>" tabindex="-1">
        <div class="modal-dialog">
          <form method="POST" action="transaksi_batal.php">
            <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi']; ?>">

            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">
                  Batalkan Transaksi ID <?= $row['id_transaksi']; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <label class="form-label">Alasan Pembatalan</label>
                <textarea name="catatan_batal" required class="form-control" rows="3"></textarea>
              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" name="submit_batal" class="btn btn-danger">Konfirmasi</button>
              </div>
            </div>
          </form>
        </div>
      </div>

    <?php else: ?>
      <span class="text-muted">Tidak ada aksi</span>
    <?php endif; ?>
  </td>

  <td>
    <a href="struk.php?id=<?= $row['id_transaksi']; ?>" target="_blank" class="btn btn-sm btn-secondary">🧾 Struk</a>
  </td>
</tr>

<?php
  endwhile;
else:
  echo "<tr><td colspan='8' class='text-muted'>Belum ada transaksi</td></tr>";
endif;
?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
.card-custom { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
.table-hover tbody tr:hover { background:#eef4ff; }
.btn { border-radius: 8px; font-size:14px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
