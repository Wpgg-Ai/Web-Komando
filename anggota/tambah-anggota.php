<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $nim      = $_POST['nim'];
    $jabatan  = $_POST['jabatan'];
    $tanggal  = $_POST['tanggal'];

    $sql = "INSERT INTO anggota (nama, email, nim, jabatan, tanggal_bergabung) 
            VALUES ('$nama', '$email', '$nim', '$jabatan', '$tanggal')";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../data-anggota.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Anggota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Anggota Baru</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" required 
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required 
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">NIM/ID</label>
                <input type="text" name="nim" required 
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Jabatan</label>
                <input type="text" name="jabatan" 
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Tanggal Bergabung</label>
                <input type="date" name="tanggal" 
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-between items-center mt-6">
                <div class="space-x-2">
                    <a href="../data-anggota.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Kembali</a>
                    <a href="../../dashboard.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Dashboard</a>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
