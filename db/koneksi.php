<?php
$host = "localhost";
$user = "root";
$pass ="";
$db = "dealer_zulfa";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("koneksi gagal: "  .$koneksi ->connect_error);
}

?>