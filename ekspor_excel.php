<?php
include 'config.php';

// Menetapkan zona waktu agar tanggal file sesuai dengan waktu lokal
date_default_timezone_set('Asia/Jakarta');

// Format nama file: Rekap_Absensi_10-01-2026.xls
$tgl_sekarang = date('d-m-Y'); 
$filename = "Rekap_Absensi_" . $tgl_sekarang . ".xls";

// Header untuk instruksi download file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");

echo "<h3>Data Rekapitulasi Absensi SIHADIR MPP - Tanggal: $tgl_sekarang</h3>";
echo "<table border='1'>
        <tr style='background-color: #f2f2f2;'>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Status Kehadiran</th>
            <th>Analisis AI</th>
            <th>Koordinat</th>
            <th>Akurasi (Meter)</th>
            <th>Waktu Absen</th>
        </tr>";

// Mengambil data dari database
$sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
while($row = mysqli_fetch_array($sql)){
    // Mengambil data sesuai kolom di image_8cae74.png
    echo "<tr>
            <td>".$row['id']."</td>
            <td>".strtoupper($row['nama'])."</td>
            <td>".$row['kategori']."</td>
            <td>".$row['status_hadir']."</td>
            <td>".$row['analisis_ai']."</td>
            <td>".$row['koordinat']."</td>
            <td>".$row['akurasi_meter']." m</td>
            <td>".date('H:i:s d-m-Y', strtotime($row['waktu_absen']))."</td>
          </tr>";
}
echo "</table>";
?>