<?php
session_start();
include "../db/koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Ambil data admin berdasarkan email
    $sql = "SELECT * FROM admin WHERE email='$email' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {

        $password_db = $admin['password']; // bisa plain text atau hash

        // 1️⃣ Jika password di database masih PLAIN TEXT
        if ($password === $password_db) {
            
            // Hash password lama agar aman
            $hashBaru = password_hash($password, PASSWORD_BCRYPT);
            $koneksi->query("UPDATE admin SET password='$hashBaru' WHERE id_admin={$admin['id_admin']}");

            // Set session
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['email'] = $admin['email'];

            header("Location:index.php");
            exit;
        }

        // 2️⃣ Jika password sudah HASH — gunakan password_verify
        if (password_verify($password, $password_db)) {

            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['email'] = $admin['email'];

            header("Location:index.php");
            exit;
        }

        // Jika tidak cocok
        $error = "❌ Email atau Password salah!";
    } else {
        $error = "❌ Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons (wajib agar icon mata muncul) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-3">Login Admin</h3>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
            <i class="bi bi-eye" id="icon-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('icon-eye');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        passwordInput.type = 'password';
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
}
</script>

</body>
</html>
