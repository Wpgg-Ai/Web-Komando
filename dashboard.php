<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

include 'koneksi.php';

// === Ambil data dari database ===

// Total anggota
$totalAnggota = $conn->query("SELECT COUNT(*) AS jml FROM anggota")->fetch_assoc()['jml'] ?? 0;

// Total kas (saldo)
$totalKas = $conn->query("SELECT SUM(jumlah) AS total FROM pembayaran_kas")->fetch_assoc()['total'] ?? 0;

// Program aktif
$programAktif = $conn->query("SELECT COUNT(*) AS aktif FROM program_kerja WHERE status='Sedang Berjalan'")->fetch_assoc()['aktif'] ?? 0;

// Total file tersimpan
$totalFiles = $conn->query("SELECT COUNT(*) AS jml FROM files")->fetch_assoc()['jml'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komando - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="assets/images/logo-light.png">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

<!-- Header -->
<header class="bg-white shadow-lg border-b-4 border-blue-500 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div class="flex justify-between items-center py-3 md:py-4">
            <div class="flex items-center space-x-2 md:space-x-4">
                <button id="sidebarToggle" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center space-x-2 md:space-x-3">
                    <img src="assets/images/logo-light.png" alt="Komando Logo" class="w-8 h-8">
                    <div class="hidden sm:block">
                        <h1 class="text-lg md:text-2xl font-bold text-gray-800">Komando</h1>
                        <p class="text-xs md:text-sm text-gray-600">Komunikasi dan Manajemen Organisasi</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2 md:space-x-4">
                <div class="flex items-center space-x-1 md:space-x-2">
                    <img src="assets/images/avatars/ava-light.png" alt="Admin Avatar" class="w-7 h-7 md:w-8 md:h-8 rounded-full">
                    <span class="hidden md:block text-sm font-medium text-gray-700">
                        <?= htmlspecialchars($_SESSION['name']); ?>
                    </span>
                </div>
                <a href="logout.php" class="px-3 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full hover:bg-red-200">
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<div class="flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 sidebar-transition lg:static lg:inset-0">
        <div class="flex flex-col h-full pt-16 lg:pt-4">
            <div class="lg:hidden flex justify-end p-4">
                <button id="sidebarClose" class="p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="flex-1 px-3 md:px-4 py-2 md:py-6 space-y-1 md:space-y-2 overflow-y-auto">
                <a href="dashboard.php" class="flex items-center px-4 py-3 text-white bg-blue-600 rounded-lg">Dashboard</a>
                <a href="pages/manajemen-kas.php" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700">Manajemen Kas</a>
                <a href="pages/manajemen-berkas.php" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700">Manajemen Berkas</a>
                <a href="pages/data-anggota.php" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700">Data Anggota</a>
                <a href="pages/program-kerja.php" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700">Program Kerja</a>
                <a href="pages/kepanitiaan.php" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700">Kepanitiaan</a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-0 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h2>
                <p class="text-gray-600">Selamat datang kembali, <?= htmlspecialchars($_SESSION['name']); ?>! Berikut ringkasan organisasi Anda.</p>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-blue-600"><?= $totalAnggota; ?></p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500">Saldo Kas</p>
                    <p class="text-3xl font-bold text-green-600">Rp <?= number_format($totalKas, 0, ',', '.'); ?></p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500">Program Aktif</p>
                    <p class="text-3xl font-bold text-purple-600"><?= $programAktif; ?></p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500">Berkas Tersimpan</p>
                    <p class="text-3xl font-bold text-orange-600"><?= $totalFiles; ?></p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8 text-gray-600">
                <p>Seluruh data di atas diperbarui secara otomatis dari sistem database Komando.</p>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');

    if (sidebarToggle) sidebarToggle.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
    if (sidebarClose) sidebarClose.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
});
</script>

</body>
</html>
