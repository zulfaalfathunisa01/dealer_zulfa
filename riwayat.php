<?php
session_start();
include "header.php";
include "db/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil riwayat transaksi beserta nama pengguna
$query_riwayat = $koneksi->query("
    SELECT t.id_transaksi, t.tanggal_transaksi, t.total_harga, t.status,
           u.nama_pengguna
    FROM transaksi t
    JOIN pengguna u ON t.pengguna_id = u.id_pengguna
    WHERE t.pengguna_id = '$id_pengguna'
    ORDER BY t.tanggal_transaksi DESC
");
?>

<div class="tab-pane fade" id="riwayat" role="tabpanel">
  <div class="scroll-area">
    <?php if ($query_riwayat && $query_riwayat->num_rows > 0): ?>
      <?php while ($row = $query_riwayat->fetch_assoc()): ?>
        <div class="order-item">
          <div class="order-header d-flex justify-content-between align-items-center">
            <div>
              <span><b>ID:</b> <?= $row['id_transaksi'] ?></span> | 
              <span><b>Nama:</b> <?= htmlspecialchars($row['nama_pengguna']) ?></span> | 
              <span><?= date('d-m-Y H:i', strtotime($row['tanggal_transaksi'])) ?> WIB</span>
            </div>
            <button class="btn btn-sm btn-primary toggle-detail">Detail</button>
          </div>
          <div class="order-detail" style="display:none; margin-top:10px; padding:10px; background:#f1f1f1; border-radius:8px;">
            <?php
              // Ambil detail transaksi
              $id_transaksi = $row['id_transaksi'];
              $detail_q = $koneksi->query("
                  SELECT p.nama_produk, p.harga, td.jumlah
                  FROM transaksi_detail td
                  JOIN produk p ON td.produk_id = p.id_produk
                  WHERE td.transaksi_id = '$id_transaksi'
              ");
            ?>
            <table class="table table-sm mb-0">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php while($det = $detail_q->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($det['nama_produk']) ?></td>
                    <td>Rp <?= number_format($det['harga'],0,',','.') ?></td>
                    <td><?= $det['jumlah'] ?></td>
                    <td>Rp <?= number_format($det['harga'] * $det['jumlah'],0,',','.') ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <p class="mt-2"><b>Total Harga:</b> Rp <?= number_format($row['total_harga'],0,',','.') ?></p>
            <p><b>Status:</b> 
              <?php
                switch($row['status']){
                    case 'proses': echo '<span class="badge bg-warning">Proses</span>'; break;
                    case 'kirim': echo '<span class="badge bg-info">Dikirim</span>'; break;
                    case 'selesai': echo '<span class="badge bg-success">Selesai</span>'; break;
                    default: echo '<span class="badge bg-danger">Batal</span>';
                }
              ?>
            </p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted mt-3">Belum ada transaksi 💔</p>
    <?php endif; ?>
  </div>
</div>

<style>
.scroll-area {
  max-height: 400px;
  overflow-y: auto;
  padding: 10px 0;
}

.order-item {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 15px;
  margin-bottom: 15px;
}

.order-header button {
  border-radius:6px;
  padding:4px 8px;
  font-size:13px;
}

.order-detail table th, .order-detail table td {
  font-size:14px;
}

.badge {
  padding: 6px 10px;
  border-radius: 6px;
  color: #fff;
  font-size: 13px;
}
.bg-warning { background: #ffc107; color: #000; }
.bg-info { background: #0dcaf0; color: #000; }
.bg-success { background: #28a745; }
.bg-danger { background: #dc3545; }
</style>

<script>
document.querySelectorAll(".toggle-detail").forEach(btn => {
  btn.addEventListener("click", function() {
    const detail = this.closest(".order-item").querySelector(".order-detail");
    detail.style.display = (detail.style.display === "none") ? "block" : "none";
  });
});
</script>
