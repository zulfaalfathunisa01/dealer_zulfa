<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="mb-4 text-primary fw-bold">📊 Dashboard Penjualan</h2>

  <?php
  // Total Pendapatan
  $totalPendapatan = $koneksi->query("
    SELECT SUM(td.harga * td.jumlah) AS total
    FROM transaksi_detail td
    JOIN transaksi t ON td.transaksi_id = t.id_transaksi
  ")->fetch_assoc()['total'] ?? 0;

  // Total Transaksi
  $totalTransaksi = $koneksi->query("
    SELECT COUNT(*) AS total FROM transaksi
  ")->fetch_assoc()['total'] ?? 0;

  // Produk Terjual
  $produkTerjual = $koneksi->query("
    SELECT SUM(jumlah) AS total FROM transaksi_detail
  ")->fetch_assoc()['total'] ?? 0;

  // Total Pengguna
  $totalUser = $koneksi->query("
    SELECT COUNT(*) AS total FROM pengguna
  ")->fetch_assoc()['total'] ?? 0;
  ?>

  <style>
    .dashboard-card {
      text-decoration: none;
      color: inherit;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: block;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
  </style>

  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <a href="index.php?page=pendapatan" class="dashboard-card">
        <div class="card text-center shadow border-0 rounded-4">
          <div class="card-body">
            <h5 class="text-success fw-bold">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h5>
            <p class="text-muted mb-0">Total Pendapatan</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="index.php?page=transaksi" class="dashboard-card">
        <div class="card text-center shadow border-0 rounded-4">
          <div class="card-body">
            <h5 class="text-primary fw-bold"><?= $totalTransaksi ?></h5>
            <p class="text-muted mb-0">Transaksi</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="index.php?page=produk_terjual" class="dashboard-card">
        <div class="card text-center shadow border-0 rounded-4">
          <div class="card-body">
            <h5 class="text-warning fw-bold"><?= $produkTerjual ?></h5>
            <p class="text-muted mb-0">Produk Terjual</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="index.php?page=user" class="dashboard-card">
        <div class="card text-center shadow border-0 rounded-4">
          <div class="card-body">
            <h5 class="text-danger fw-bold"><?= $totalUser ?></h5>
            <p class="text-muted mb-0">Total Pengguna</p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- TABEL TRANSAKSI TERBARU -->
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">🧾 Transaksi & Booking Terbaru</h5>

        <button class="btn btn-success btn-sm" onclick="cetakTransaksi()">🖨️ Cetak Laporan</button>
      </div>

      <!-- Filter -->
      <div class="row g-2 mb-3">
        <div class="col-md-4">
          <input type="text" id="cariTransaksi" onkeyup="filterTransaksi()" class="form-control"
            placeholder="🔍 Cari nama pengguna / produk...">
        </div>
        <div class="col-md-3">
          <input type="date" id="tanggalMulai" class="form-control" onchange="filterTransaksi()">
        </div>
        <div class="col-md-3">
          <input type="date" id="tanggalAkhir" class="form-control" onchange="filterTransaksi()">
        </div>
        <div class="col-md-2">
          <button class="btn btn-secondary w-100" onclick="resetFilter()">🔄 Reset</button>
        </div>
      </div>

      <div class="table-responsive" id="tabelTransaksi">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nomor Booking</th>
              <th>Nama Pengguna</th>
              <th>Produk</th>
              <th>Jumlah</th>
              <th>Total Harga</th>
              <th>Tanggal</th>
            </tr>
          </thead>
<tbody>
<?php
$sql = "
  SELECT 
    t.id_transaksi,
    t.nomor_booking,
    p.nama_pengguna AS nama_user, 
    pr.nama_produk, 
    td.jumlah, 
    (td.harga * td.jumlah) AS total_harga, 
    t.tanggal_transaksi
  FROM transaksi t
  LEFT JOIN pengguna p ON t.pengguna_id = p.id_pengguna
  JOIN transaksi_detail td ON t.id_transaksi = td.transaksi_id
  JOIN produk pr ON td.produk_id = pr.id_produk
  ORDER BY t.tanggal_transaksi DESC
";

$result = $koneksi->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

    echo "
    <tr>
      <td><span class='badge bg-primary'>{$row['id_transaksi']}</span></td>

      <!-- NOMOR BOOKING TANPA BADGE -->
      <td>{$row['nomor_booking']}</td>

      <td>" . htmlspecialchars($row['nama_user'] ?? 'Tidak Diketahui') . "</td>
      <td>{$row['nama_produk']}</td>
      <td>{$row['jumlah']}</td>
      <td><strong>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</strong></td>
      <td>{$row['tanggal_transaksi']}</td>
    </tr>
    ";
  }
} else {
  echo "<tr><td colspan='7' class='text-center text-muted py-4'>Belum ada transaksi</td></tr>";
}
?>
</tbody>

        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function filterTransaksi() {
    const input = document.getElementById("cariTransaksi").value.toLowerCase();
    const start = document.getElementById("tanggalMulai").value;
    const end = document.getElementById("tanggalAkhir").value;
    const rows = document.querySelectorAll("#tabelTransaksi tbody tr");

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const tanggalCell = row.cells[6]?.textContent.trim();
      let tampil = true;

      if (input && !text.includes(input)) tampil = false;

      if (start || end) {
        const tanggalData = new Date(tanggalCell);
        const mulai = start ? new Date(start) : null;
        const akhir = end ? new Date(end) : null;

        if (mulai && tanggalData < mulai) tampil = false;
        if (akhir && tanggalData > akhir) tampil = false;
      }

      row.style.display = tampil ? "" : "none";
    });
  }

  function resetFilter() {
    document.getElementById("cariTransaksi").value = "";
    document.getElementById("tanggalMulai").value = "";
    document.getElementById("tanggalAkhir").value = "";
    filterTransaksi();
  }

  function cetakTransaksi() {
    const tabel = document.getElementById("tabelTransaksi").innerHTML;
    const win = window.open('', '', 'width=900,height=650');
    win.document.write(`
      <html>
      <head>
        <title>Laporan Transaksi</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 30px; }
          h2 { text-align: center; margin-bottom: 20px; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #000; padding: 8px; text-align: center; }
          th { background-color: #f2f2f2; }
        </style>
      </head>
      <body>
        <h2>Laporan Transaksi Dealer</h2>
        <p><strong>Tanggal Cetak:</strong> ${new Date().toLocaleDateString()}</p>
        <table>${tabel}</table>
      </body>
      </html>
    `);
    win.document.close();
    win.print();
  }
</script>
