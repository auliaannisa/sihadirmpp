<?php
include 'config.php';

/* =========================
   AMBIL DATA FORM
========================= */
$nama          = mysqli_real_escape_string($conn, $_POST['nama']);
$kategori      = $_POST['kategori'];
$status_hadir  = $_POST['status_hadir'];
$keterangan    = isset($_POST['keterangan']) 
                ? mysqli_real_escape_string($conn, $_POST['keterangan']) 
                : null;
$koordinat     = $_POST['koordinat'];
$akurasi       = $_POST['akurasi'];
$jam           = date('H:i');

/* =========================
   ANALISIS AI
========================= */
$ai_notes = [];
$ai_notes[] = ($jam > "08:00") ? "Terlambat" : "Tepat Waktu";
$ai_notes[] = ($akurasi > 50) ? "Lokasi Kurang Akurat" : "Lokasi Valid";
$status_ai = implode(" | ", $ai_notes);

/* =========================
   PROSES FOTO / FILE
========================= */
$nama_file = "";

/* === HADIR → FOTO SELFIE === */
if ($status_hadir == "Hadir" && !empty($_POST['foto_base64'])) {

    $foto_data = $_POST['foto_base64'];
    $foto_data = str_replace('data:image/jpeg;base64,', '', $foto_data);
    $foto_data = str_replace(' ', '+', $foto_data);
    $data = base64_decode($foto_data);

    $nama_file = "Selfie_" . time() . ".jpg";
    file_put_contents("uploads/" . $nama_file, $data);
}

/* === IZIN / SAKIT → FILE SURAT === */
if (($status_hadir == "Izin" || $status_hadir == "Sakit") && isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {

    $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
    $nama_file = "Surat_" . time() . "." . $ext;
    move_uploaded_file($_FILES['file_surat']['tmp_name'], "uploads/" . $nama_file);
}

/* =========================
   SIMPAN KE DATABASE
========================= */
$query = "INSERT INTO absensi 
(nama, kategori, status_hadir, keterangan, foto, koordinat, akurasi_meter, analisis_ai)
VALUES
('$nama', '$kategori', '$status_hadir', '$keterangan', '$nama_file', '$koordinat', '$akurasi', '$status_ai')";

mysqli_query($conn, $query);

/* =========================
   REDIRECT
========================= */
header("Location: dashboard.php");
exit;
?>
