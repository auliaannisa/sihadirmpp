<?php
include 'config.php';

// Set zona waktu agar sinkron dengan WIB
date_default_timezone_set('Asia/Jakarta');

/* =========================
   AMBIL DATA FORM
========================= */
$nama          = mysqli_real_escape_string($conn, $_POST['nama']);
$kategori      = $_POST['kategori'];
$status_hadir  = $_POST['status_hadir'];

// --- TAMBAHAN BARU: TANGKAP DATA QR ---
$metode_absen  = isset($_POST['metode_absen']) ? $_POST['metode_absen'] : 'Manual';
$qr_content    = isset($_POST['qr_content']) ? mysqli_real_escape_string($conn, $_POST['qr_content']) : '-';
// --------------------------------------

// Perbaikan Logika Keterangan: Jika kosong, berikan tanda strip (-)
$keterangan    = (!empty($_POST['keterangan'])) 
                 ? mysqli_real_escape_string($conn, $_POST['keterangan']) 
                 : "-";

// Jika menggunakan QR, masukkan isi QR ke keterangan jika keterangan manual kosong
if ($metode_absen == "QR Code" && $keterangan == "-") {
    $keterangan = "Scan QR: " . $qr_content;
}

$koordinat     = $_POST['koordinat'];
$akurasi       = isset($_POST['akurasi']) ? $_POST['akurasi'] : 0;
$jam_sekarang  = date('H:i'); 

/* =========================
   LOGIKA ANALISIS AI
========================= */
$ai_notes = [];

// 1. Validasi Waktu: Terlambat jika > 07:30
if ($jam_sekarang > "07:30") {
    $ai_notes[] = "Terlambat";
} else {
    $ai_notes[] = "Tepat Waktu";
}

// 2. Validasi Jarak/Akurasi Lokasi
// Jika QR Code, kita anggap lokasi sudah diverifikasi secara fisik
if ($metode_absen == "QR Code") {
    $ai_notes[] = "Verified via QR";
} else {
    if ($akurasi > 100) {
        $ai_notes[] = "Lokasi Kurang Akurat";
    } else {
        $ai_notes[] = "Lokasi Valid";
    }
}

$status_ai = implode(" | ", $ai_notes);

/* =========================
   PROSES FOTO / FILE
========================= */
$nama_file = "";

// A. JIKA STATUS HADIR (MENGGUNAKAN KAMERA + WATERMARK)
if ($status_hadir == "Hadir" && !empty($_POST['foto_base64'])) {
    $foto_data = $_POST['foto_base64'];
    $foto_data = str_replace('data:image/jpeg;base64,', '', $foto_data);
    $foto_data = str_replace(' ', '+', $foto_data);
    $data = base64_decode($foto_data);

    $image = imagecreatefromstring($data);
    if ($image !== false) {
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Watermark ditambahkan info metode (Manual/QR)
        $text = "SIHADIR MPP - " . date('d/m/Y H:i:s') . " [" . $metode_absen . "]";
        
        imagestring($image, 5, 12, 12, $text, $black); 
        imagestring($image, 5, 10, 10, $text, $white); 

        $nama_file = "Selfie_" . time() . ".jpg";
        imagejpeg($image, "uploads/" . $nama_file, 90);
        imagedestroy($image);
    }
} 
// B. JIKA STATUS IZIN/SAKIT (MENGGUNAKAN UPLOAD FILE SURAT)
elseif (($status_hadir == "Izin" || $status_hadir == "Sakit") && isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
    $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
    $nama_file = "Surat_" . time() . "." . $ext;
    move_uploaded_file($_FILES['file_surat']['tmp_name'], "uploads/" . $nama_file);
}
// Titik Koordinat Kantor (Contoh: Kantor BKPSDM)
$lat_kantor = -6.123456; 
$lon_kantor = 106.123456;
$radius_maksimal = 100; // dalam meter

function getDistance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return ($miles * 1609.344); // Konversi ke Meter
}

// Ambil koordinat user
list($u_lat, $u_lon) = explode(',', $koordinat);
$jarak = getDistance($u_lat, $u_lon, $lat_kantor, $lon_kantor);

if ($jarak > $radius_maksimal && $metode_absen == "Manual") {
   
}

/* =========================
   SIMPAN KE DATABASE
========================= */
// Tambahkan kolom 'metode_absen' ke dalam query INSERT
$query = "INSERT INTO absensi 
(nama, kategori, status_hadir, metode_absen, keterangan, foto, koordinat, akurasi_meter, analisis_ai, waktu_absen)
VALUES
('$nama', '$kategori', '$status_hadir', '$metode_absen', '$keterangan', '$nama_file', '$koordinat', '$akurasi', '$status_ai', NOW())";

if(mysqli_query($conn, $query)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
?>