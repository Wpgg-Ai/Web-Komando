<?php
$servername = "localhost";
$username   = "root";   // default XAMPP
$password   = "";       // default XAMPP kosong
$dbname     = "webku";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>