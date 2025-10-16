<?php
$page_title = "Register - My Website";
include 'header.php';
include 'db/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_pengguna = trim($_POST['nama_pengguna']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    // Cek apakah email sudah terdaftar
    $stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan! Silakan gunakan email lain.');</script>";
    } else {
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Simpan ke database
        $stmt = $koneksi->prepare("INSERT INTO pengguna (nama_pengguna, email, password, no_hp, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama_pengguna, $email, $hash, $no_hp, $alamat);

        if ($stmt->execute()) {
            echo "<script>
                alert('Registrasi berhasil! Silakan login sekarang.');
                window.location='login.php';
            </script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menyimpan data.');</script>";
        }
    }
}
?>

<!-- Tampilan Form Registrasi -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="card shadow-lg border-0 rounded-4" style="width: 400px;">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 text-primary fw-bold">Daftar Akun</h3>

      <form action="" method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" name="nama_pengguna" placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" placeholder="Masukkan email aktif" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter" required>
        </div>

        <div class="mb-3">
          <label class="form-label">No. HP</label>
          <input type="tel" class="form-control" name="no_hp" placeholder="Contoh: 081234567890">
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap Anda"></textarea>
        </div>

        <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Daftar Sekarang</button>

        <div class="text-center mt-3">
          <small>Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Login di sini</a></small>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
