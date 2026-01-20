<?php
include 'config.php';

// Proteksi Admin
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Tangkap Parameter Bulan & Tahun
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=Laporan_Absensi_{$bulan}_{$tahun}.csv");

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

fputcsv($output, array('ID', 'Nama', 'Kategori', 'Status', 'Metode', 'Keterangan', 'Koordinat', 'Waktu', 'Analisis AI'));

// Query Filter Per Bulan
$query = mysqli_query($conn, "SELECT id, nama, kategori, status_hadir, metode_absen, keterangan, koordinat, waktu_absen, analisis_ai 
                              FROM absensi 
                              WHERE MONTH(waktu_absen) = '$bulan' AND YEAR(waktu_absen) = '$tahun' 
                              ORDER BY waktu_absen ASC");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>