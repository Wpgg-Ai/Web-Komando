<?php
session_start();
include 'koneksi.php';

// Inisialisasi pesan
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Email sudah terdaftar!";
    } else {
        // Simpan data baru
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $success = true;
            $message = "Registrasi berhasil! Anda akan diarahkan ke halaman login...";
        } else {
            $message = "Terjadi kesalahan: " . $conn->error;
        }
    }

    $stmt->close();

    // Redirect otomatis jika sukses
    if ($success) {
        echo "<script>
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2500);
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Komando</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="assets/images/logo-light.png">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 mx-4">
    <div class="text-center mb-6">
        <img src="assets/images/logo-light.png" alt="Logo Komando" class="w-16 h-16 mx-auto mb-3">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Akun Komando</h1>
        <p class="text-gray-500 text-sm">Isi data di bawah untuk membuat akun baru</p>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-3 rounded-lg text-center text-sm font-medium
            <?= $success ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Daftar
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Sudah punya akun?
        <a href="index.php" class="text-blue-600 hover:underline font-medium">Login di sini</a>
    </p>
</div>

</body>
</html>
