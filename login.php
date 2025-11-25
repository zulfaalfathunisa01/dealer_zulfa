<?php
session_start();
include 'header.php';
include 'db/koneksi.php';

$redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : "index.php";

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
                alert('Login berhasil!');
                location.href='" . htmlspecialchars($redirect_url, ENT_QUOTES) . "';
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

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Akun</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons (WAJIB supaya icon mata muncul) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body class="bg-light">

<!-- Tampilan Form Login -->
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg border-0 rounded-4" style="width: 400px;">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 text-primary fw-bold">Login Akun</h3>

      <form method="POST">

        <!-- EMAIL -->
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
        </div>

        <!-- PASSWORD + SHOW/HIDE -->
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
            <span class="input-group-text bg-white" id="togglePassword" style="cursor: pointer;">
              <i class="bi bi-eye" id="icon-eye"></i>
            </span>
          </div>
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

<!-- Script Toggle Mata -->
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
  const passInput = document.getElementById('password');
  const icon = document.getElementById('icon-eye');

  if (passInput.type === 'password') {
    passInput.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    passInput.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  }
});
</script>

<?php include 'footer.php'; ?>

</body>
</html>
