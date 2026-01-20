<?php
$conn = mysqli_connect("localhost", "root", "", "sihadirmpp");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>