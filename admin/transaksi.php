<?php
include "../db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');


// Ubah status transaksi
if (isset($_GET['ubah_status']) && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $status = $_GET['ubah_status'];
  mysqli_query($koneksi, "UPDATE transaksi SET status='$status' WHERE id_transaksi=$id");
  echo "<script>window.location.href='?page=transaksi';</script>";
  exit;
}
?>

<div class="container-fluid">
  <h2 class="fw-bold text-primary mb-4">
    <i class="bi bi-cart-check"></i> Kelola Transaksi
  </h2>

  <!-- Kotak Pencarian -->
  <div class="card card-custom p-3 mb-4 bg-white border-0">
    <form method="GET" class="d-flex align-items-center">
      <input type="hidden" name="page" value="transaksi">
      <input
        type="text"
        name="search"
        class="form-control me-2"
        placeholder="Cari berdasarkan ID / Status transaksi..."
        value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
      <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <!-- Daftar Transaksi -->
<div class="card card-custom p-3 bg-white border-0">
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle text-center mb-0">
      <thead class="text-white" style="background-color:#0d6efd;">
        <tr>
          <th>ID</th>
          <th>Nama Pengguna</th> <!-- Ubah header -->
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
        $query = "SELECT t.*, p.nama_pengguna 
                  FROM transaksi t
                  JOIN pengguna p ON t.pengguna_id = p.id_pengguna
                  WHERE t.id_transaksi LIKE '%$search%' 
                     OR t.status LIKE '%$search%' 
                     OR p.nama_pengguna LIKE '%$search%'
                  ORDER BY t.id_transaksi DESC";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $status = strtolower($row['status']);
            $badgeClass = [
              'pesanan dibuat' => 'secondary',
              'proses' => 'primary',
              'kirim' => 'info text-dark',
              'selesai' => 'success',
              'batal' => 'danger'
            ];
            $class = $badgeClass[$status] ?? 'secondary';
        ?>
            <tr>
              <td><?= $row['id_transaksi']; ?></td>
              <td><?= $row['nama_pengguna']; ?></td> <!-- Menampilkan nama pengguna -->
              <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
              <td><?= date('d F Y, H:i', strtotime($row['tanggal_transaksi'])); ?> WIB</td>
              <td><span class="badge bg-<?= $class ?> text-capitalize"><?= ucfirst($status) ?></span></td>
              <td>
                <?php if ($status == 'pesanan dibuat') { ?>
                  <a href="?page=transaksi&ubah_status=proses&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-primary me-1">Proses</a>
                  <a href="?page=transaksi&ubah_status=batal&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-danger">Batal</a>
                <?php } elseif ($status == 'proses') { ?>
                  <a href="?page=transaksi&ubah_status=kirim&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-info text-white me-1">Kirim</a>
                  <a href="?page=transaksi&ubah_status=batal&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-danger">Batal</a>
                <?php } elseif ($status == 'kirim') { ?>
                  <a href="?page=transaksi&ubah_status=selesai&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-success me-1">Selesai</a>
                  <a href="?page=transaksi&ubah_status=batal&id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-danger">Batal</a>
                <?php } else { ?>
                  <span class="text-muted">Tidak ada aksi</span>
                <?php } ?>
              </td>
              <td>
                <a href="struk.php?id=<?= $row['id_transaksi']; ?>" target="_blank" class="btn btn-sm btn-secondary">🧾 Struk</a>
              </td>
            </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='7' class='text-muted'>Belum ada data transaksi</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>


<style>
  .card-custom {
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    background: #fff;
  }

  .table thead th {
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #eef4ff;
  }

  .btn {
    border-radius: 8px;
    font-size: 14px;
    padding: 5px 10px;
  }
</style>