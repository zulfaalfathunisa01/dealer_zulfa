<?php
$page_title = "Register - My Website";
include 'header.php';
include 'db/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama_pengguna = trim($_POST['nama_pengguna']);
    $email         = trim($_POST['email']);
    $password      = $_POST['password'];
    $no_hp         = trim($_POST['no_hp']);
    $alamat        = trim($_POST['alamat']);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');</script>";
        exit;
    }

    // Cek email duplikat
    $cek = $koneksi->prepare("SELECT id_pengguna FROM pengguna WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "<script>
                alert('Email sudah digunakan! Silakan gunakan email lain.');
                window.history.back();
              </script>";
        exit;
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert data
    $stmt = $koneksi->prepare("
        INSERT INTO pengguna (nama_pengguna, email, password, no_hp, alamat)
        VALUES (?, ?, ?, ?, ?)
    ");
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
?>

<!-- FORM REGISTER -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="card shadow-lg border-0 rounded-4" style="width: 400px;">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 text-primary fw-bold">Daftar Akun</h3>

      <form action="" method="POST" novalidate>
        
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" name="nama_pengguna" required
                 placeholder="Masukkan nama lengkap Anda">
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required
                 placeholder="Masukkan email aktif">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" name="password"
                   id="password" minlength="6" required
                   placeholder="Minimal 6 karakter">
            <button type="button" class="btn btn-outline-secondary"
                    onclick="togglePassword('password', this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">No. HP</label>
          <input type="tel" class="form-control" name="no_hp"
                 pattern="[0-9]{10,13}" placeholder="Contoh: 081234567890">
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea class="form-control" name="alamat" rows="2"
                    placeholder="Alamat lengkap Anda"></textarea>
        </div>

        <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">
          Daftar Sekarang
        </button>

        <div class="text-center mt-3">
          <small>Sudah punya akun?
            <a href="login.php" class="text-decoration-none text-primary">
              Login di sini
            </a>
          </small>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
}
</script>

<?php include 'footer.php'; ?>
