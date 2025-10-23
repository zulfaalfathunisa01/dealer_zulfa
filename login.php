<?php
$page_title = "Login - My Website";
include 'header.php';
include 'db/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Cek user berdasarkan email
    $stmt = $koneksi->prepare("SELECT id_pengguna, nama_pengguna, password FROM pengguna WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['nama'] = $user['nama_pengguna'];

            echo "<script>
                alert('Login berhasil! Selamat datang, " . htmlspecialchars($user['nama_pengguna']) . "');
                window.location='index.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}
?>

<!-- Tampilan Form Login -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="card shadow-lg border-0 rounded-4" style="width: 400px;">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 text-primary fw-bold">Login Akun</h3>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Login</button>

        <div class="text-center mt-3">
          <small>Belum punya akun? 
            <a href="register.php" class="text-decoration-none text-primary">Daftar di sini</a>
          </small>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
