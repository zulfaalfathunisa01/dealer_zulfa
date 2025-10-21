<?php
session_start();
include "db/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data pengguna
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
$pengguna = mysqli_fetch_assoc($query);

// Jika belum ada data pengguna
if (!$pengguna) {
    $pengguna = [
        'nama' => '',
        'email' => '',
        'telepon' => '',
        'alamat' => ''
    ];
}

// Update data profil
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update = mysqli_query($koneksi, "UPDATE pengguna 
        SET nama='$nama', email='$email', telepon='$telepon', alamat='$alamat'
        WHERE id_pengguna='$id_pengguna'");

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            font-family: Poppins, sans-serif; 
            background: #f8f9fa; 
            margin: 0; 
            padding: 20px; 
        }
        .container { 
            max-width: 800px; 
            margin: auto; 
            background: white; 
            border-radius: 12px; 
            padding: 20px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
        }
        h2 { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .tabs { 
            display: flex; 
            justify-content: space-around; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #eee; 
        }
        .tab a { 
            display: block;
            padding: 10px 15px; 
            font-weight: 600; 
            color: #555; 
            text-decoration: none;
        }
        .tab a.active { 
            border-bottom: 3px solid #007bff; 
            color: #007bff; 
        }
        form input, form textarea { 
            width: 100%; 
            padding: 10px; 
            margin: 8px 0; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
        }
        button { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #0056b3; 
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Profil Pengguna</h2>

    <div class="tabs">
        <div class="tab"><a href="profil.php" class="active">Edit Profil</a></div>
        <div class="tab"><a href="wishlist.php">Wishlist</a></div>
        <div class="tab"><a href="riwayat.php">Riwayat</a></div>
        <div class="tab"><a href="produk_keranjang.php">Keranjang</a></div>
    </div>

    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($pengguna['nama'] ?? '', ENT_QUOTES) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($pengguna['email'] ?? '', ENT_QUOTES) ?>" required>

        <label>Telepon:</label>
        <input type="text" name="telepon" value="<?= htmlspecialchars($pengguna['telepon'] ?? '', ENT_QUOTES) ?>">

        <label>Alamat:</label>
        <textarea name="alamat"><?= htmlspecialchars($pengguna['alamat'] ?? '', ENT_QUOTES) ?></textarea>

        <button type="submit" name="simpan">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
