<?php
include "../db/koneksi.php";

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

$id_admin = $_SESSION['id_admin'];
$notif = "";

// Ambil data admin
$q = $koneksi->query("SELECT email FROM admin WHERE id_admin=$id_admin");
$admin = $q->fetch_assoc();

// --- Proses update ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Update email
    if (isset($_POST['update_email'])) {
        $email_baru = trim($_POST['email']);
        if (!empty($email_baru)) {
            $koneksi->query("UPDATE admin SET email='$email_baru' WHERE id_admin=$id_admin");
            $_SESSION['email'] = $email_baru;
            $notif = "<div class='alert alert-success'>Email berhasil diperbarui!</div>";
        }
    }

    // Update password
    if (isset($_POST['update_password'])) {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi = $_POST['konfirmasi_password'];

        $cek = $koneksi->query("SELECT password FROM admin WHERE id_admin=$id_admin")->fetch_assoc();

        if (!password_verify($password_lama, $cek['password'])) {
            $notif = "<div class='alert alert-danger'>Password lama salah!</div>";
        } elseif ($password_baru !== $konfirmasi) {
            $notif = "<div class='alert alert-warning'>Konfirmasi password tidak cocok!</div>";
        } else {
            $hash_baru = password_hash($password_baru, PASSWORD_BCRYPT);
            $koneksi->query("UPDATE admin SET password='$hash_baru' WHERE id_admin=$id_admin");
            $notif = "<div class='alert alert-success'>Password berhasil diperbarui!</div>";
        }
    }
}
?>

<div class="min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow border-0 p-4 card-settings">
        <h3 class="mb-3">⚙️ Pengaturan Admin</h3>

        <?= $notif ?>

        <!-- Update Email -->
        <form method="post">
            <h5>📧 Ubah Email</h5>

            <div class="mb-3">
                <label>Email Baru</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="form-control" required>
            </div>

            <button type="submit" name="update_email" class="btn btn-primary w-100">Simpan Email</button>
        </form>

        <hr class="my-4">

        <!-- Update Password -->
        <form method="post">
            <h5>🔒 Ubah Password</h5>

            <div class="mb-3 position-relative">
                <label>Password Lama</label>
                <input type="password" name="password_lama" id="lama" class="form-control" required>
                <i class="bi bi-eye-slash toggle-eye" onclick="toggle('lama', this)"></i>
            </div>

            <div class="mb-3 position-relative">
                <label>Password Baru</label>
                <input type="password" name="password_baru" id="baru" class="form-control" required>
                <i class="bi bi-eye-slash toggle-eye" onclick="toggle('baru', this)"></i>
            </div>

            <div class="mb-3 position-relative">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password" id="confirm" class="form-control" required>
                <i class="bi bi-eye-slash toggle-eye" onclick="toggle('confirm', this)"></i>
            </div>

            <button type="submit" name="update_password" class="btn btn-success w-100">Simpan Password</button>
        </form>
    </div>
</div>

<style>
.card-settings {
    max-width: 450px;
    width: 100%;
    border-radius: 12px;
}

.toggle-eye {
    position: absolute;
    right: 12px;
    top: 38px;
    cursor: pointer;
    font-size: 20px;
    color: #777;
}
.toggle-eye:hover {
    color: black;
}
</style>

<script>
function toggle(id, el) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        el.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        input.type = "password";
        el.classList.replace("bi-eye", "bi-eye-slash");
    }
}
</script>

