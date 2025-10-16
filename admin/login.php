<?php
session_start();
include "../db/koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE email='$email' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['nama_admin'] = $admin['nama'];

        header("Location:index.php");
        exit;
    } else {
        $error = "❌ Email atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">🔑 Login</button>
    </form>
  </div>
</body>
</html>
