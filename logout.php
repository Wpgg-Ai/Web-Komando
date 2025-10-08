<?php
session_start();
session_unset();   // hapus semua session
session_destroy(); // hancurkan session

// kembali ke halaman login
header("Location: index.php");
exit;
?>
