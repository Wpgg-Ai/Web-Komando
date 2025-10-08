<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../koneksi.php';

$id = $_GET['id'];
$conn->query("DELETE FROM anggota WHERE id=$id");

header("Location: ../data-anggota.php");
exit;
?>
