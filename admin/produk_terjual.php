<?php
include "../db/koneksi.php";
?>

<div class="container mt-4">
  <h2 class="mb-4 text-primary fw-bold">📦 Daftar Produk Terjual</h2>

  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Jumlah Terjual</th>
              <th>Total Pendapatan</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "
              SELECT 
                pr.nama_produk,
                pr.kategori,
                SUM(td.jumlah) AS total_terjual,
                SUM(td.harga * td.jumlah) AS total_pendapatan
              FROM transaksi_detail td
              JOIN produk pr ON td.produk_id = pr.id_produk
              GROUP BY td.produk_id
              ORDER BY total_terjual DESC
            ";

            $result = $koneksi->query($sql);
            $no = 1;

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "
                  <tr>
                    <td>{$no}</td>
                    <td>{$row['nama_produk']}</td>
                    <td>{$row['kategori']}</td>
                    <td>{$row['total_terjual']}</td>
                    <td><strong>Rp " . number_format($row['total_pendapatan'], 0, ',', '.') . "</strong></td>
                  </tr>
                ";
                $no++;
              }
            } else {
              echo "<tr><td colspan='5' class='text-muted py-4'>Belum ada produk terjual.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
