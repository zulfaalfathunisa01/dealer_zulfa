<?php
include "../db/koneksi.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$nama_merk = $_POST['nama_merk'];

$sql = "INSERT INTO merk (nama_merk) VALUES ('$nama_merk')";
if ($koneksi->query($sql) === TRUE) {
    echo "Merk berhasil disimpan.<br>";
  header('location:index.php?page=merk');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Merk Motor</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f3f4f6; padding:20px;}
    .container { max-width:500px; margin:auto; background:white; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.1);}
    h2 { text-align:center; margin-bottom:20px; }
    label { font-weight:bold; display:block; margin-top:15px;}
    input { width:100%; padding:10px; margin-top:8px; border-radius:6px; border:1px solid #ccc;}
    button { margin-top:20px; width:100%; padding:12px; border:none; border-radius:8px; background:#007bff; color:white; font-size:16px; cursor:pointer;}
    button:hover { background:#0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Tambah Merk Motor</h2>
    <form action="" method="post">
      <label for="nama_merk">Nama Merk</label>
      <input type="text" id="nama_merk" name="nama_merk" required>

      <button type="submit">Simpan Merk</button>
    </form>
  </div>
</body>
</html>