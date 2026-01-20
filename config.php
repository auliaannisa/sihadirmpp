<?php
// Mengambil data dari Variables Railway secara otomatis
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

// Melakukan koneksi ke database online Railway
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi ke database Railway gagal: " . mysqli_connect_error());
}
?>