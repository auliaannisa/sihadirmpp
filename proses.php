<?php
include 'config.php';

$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$koordinat = $_POST['koordinat'];
$akurasi = $_POST['akurasi'];
$keterangan = $_POST['keterangan']; 
$jam = date('H:i');

// --- Logika AI SIHADIR MPP ---
$ai_notes = [];

// 1. Deteksi Keterlambatan
if ($jam > "08:00") { $ai_notes[] = "🔴 Terlambat"; } 
else { $ai_notes[] = "🟢 Tepat Waktu"; }

// 2. Deteksi Keakuratan Lokasi (GPS AI)
if ($akurasi > 100) { $ai_notes[] = "⚠️ Lokasi Kurang Akurat"; }
else { $ai_notes[] = "✅ Lokasi Presisi"; }

// 3. Deteksi Kelengkapan
if (empty($_FILES['foto']['name'])) { $ai_notes[] = "❌ Foto Tidak Ada"; }
else { $ai_notes[] = "✅ Berkas Lengkap"; }

$hasil_ai = implode(" | ", $ai_notes);

// Upload Foto
$target_dir = "uploads/";
$nama_foto = time() . "_" . $_FILES["foto"]["name"];
move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $nama_foto);

$sql = "INSERT INTO absensi (nama, kategori, foto, koordinat, akurasi_meter, analisis_ai) 
        VALUES ('$nama', '$kategori', '$nama_foto', '$koordinat', '$akurasi', '$hasil_ai')";

if (mysqli_query($conn, $sql)) {
    header("Location: riwayat.php");
}
?>