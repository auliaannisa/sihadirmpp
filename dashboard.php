<?php include 'config.php'; 
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'peserta') header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIHADIR MPP BKPSDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        :root {
            --primary-blue: #070136ff;
            --secondary-blue: #1b05e4ff;
            --light-blue: #f2f8feff;
        }

        body {
            background-color: var(--light-blue);
            font-family: 'Segoe UI', sans-serif;
            color: #1626d8ff ;
        }

        /* Header Gradient Section */
        .header-section {
            background: linear-gradient(135deg, #1b05e4ff 0%, #070136ff 100%);
            padding: 50px 20px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            text-align: center;
            color: white;
            box-shadow: 0 10px 20px rgba(9, 9, 224, 0.35);
            margin-bottom: -60px;
        }

        .header-section h1 { font-weight: 800; letter-spacing: 1px; margin-top: 10px; }
        .header-section p { font-size: 0.9rem; opacity: 0.9; }

        /* Main Card Container */
        .main-container {
            max-width: 950px;
            margin: 0 auto 50px auto;
            padding: 0 15px;
        }

        .content-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(15, 3, 79, 0.61);
        }

        .form-label { font-weight: 700; font-size: 0.75rem; color: #0f54b5ff ; text-transform: uppercase; margin-bottom: 8px; }
        
        .form-control, .form-select {
            background-color: #f7f4f5ff;
            border: 1px solid #0a1177ff ;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        /* Camera Viewfinder */
        #camera, #canvas {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            background: #2908fa96;
            margin: 10px auto;
            border: 3px solid #070136ff ;
            box-shadow: 0 5px 15px rgba(14, 2, 76, 0.87);
        }
        #canvas { display: none; }

        /* Custom Blue Buttons */
        .btn-blue { background-color: #1b05e4ff; color: white; border-radius: 10px; font-weight: 600; padding: 10px 20px; border: none; }
        .btn-blue:hover { background-color: #070136ff; color: white; }
        
        .btn-simpan {
            background: linear-gradient(to right, #1b05e4ff, #070136ff);
            border: none;
            padding: 15px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,114,255,0.3);
        }

        .input-group-text { background-color: #0d15f8ff; color: white; border: none; border-radius: 10px !important; margin-left: 5px; cursor: pointer; }
        
        .btn-logout { position: absolute; top: 20px; right: 20px; color: white; text-decoration: none; font-weight: 600; }

    /* Container Tabel agar sudut membulat sempurna */
.history-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(221, 210, 210, 0.9);
    margin-top: 30px;
}

/* Container Utama */
.history-container {
    width: 100%;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(14, 5, 143, 0.86);
    margin-top: 30px;
    border: 1px solid #ddd;
}

/* Header Tabel dengan Gradasi Biru sesuai gambar */
.table-siampp thead {
    width: 100%;
    /* Gradasi dari Biru Terang ke Biru Tua */
    background: linear-gradient(to right, #1b05e4ff, #070136ff, #1b05e4ff, #070136ff) !important;
    color: white !important;
}

.table-siampp th {
    background: transparent !important; /* Agar warna gradient di thead terlihat */
    padding: 15px !important;
    text-align: center !important;
    font-size: 0.85rem !important;
    text-transform: uppercase !important;
    font-weight: bold !important;
    border: none !important;
    color: white !important;
}

/* Garis pemisah antar kolom di header*/
.table-siampp th:not(:last-child) {
    border-right: 1px solid rgba(255, 255, 255, 0.21) !important;
}

/* Baris Tabel */
.table-siampp td {
    padding: 12px 15px !important;
    border-bottom: 1px solid #1301b9ff !important;
    text-align: center !important;
    vertical-align: middle !important;
    color: #0b025cff !important;
}
/* Warna Navy Gelap untuk semua tombol aksi utama */
.btn-navy-custom {
    background-color: #000033 !important; /* Navy gelap sesuai gambar */
    color: white !important;
    border-radius: 12px !important; /* Sudut membulat halus */
    padding: 10px 20px !important;
    font-weight: 600 !important;
    border: none !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 140px; /* Menjaga ukuran tombol agar seragam */
    transition: all 0.3s ease;
}

.btn-navy-custom i {
    margin-right: 8px; /* Jarak antara ikon dan teks */
}

.btn-navy-custom:hover {
    background-color: #000055 !important;
    transform: translateY(-1px);
}

/* Mengatur jarak antar input group */
.gap-2 {
    gap: 0.75rem !important; /* Mengatur lebar spasi pemisah antar tombol/input */
}

/* Pastikan input tidak terlalu lebar sehingga memakan tempat tombol */
.form-control {
    flex: 1; /* Input akan mengisi ruang yang tersisa */
}

/* Container Header Riwayat */
.history-header {
    background: #ffffff;
    border-radius: 15px 15px 0 0; /* Bulat di atas saja agar menyatu dengan tabel */
    padding: 20px;
    display: flex;
    justify-content: space-between; /* Judul di kiri, tombol di kanan */
    align-items: center;
    border-bottom: 2px solid #f0f0f0;
}

.history-title {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #1e3c72;
    font-weight: 800;
}

/* Mengunci ukuran ikon agar tidak meluber */
.history-title img {
    width: 35px !important;
    height: 35px !important;
    object-fit: contain;
}

.history-title span {
    font-size: 1.4rem;
    letter-spacing: -0.5px;
}

/* Container Tombol */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-action {
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    color: white;
    transition: 0.3s;
    text-decoration: none;
}

.btn-export { background-color: #2563eb; }
.btn-refresh { background-color: #3b82f6; }

.btn-action:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    color: white;
}
    </style>

</head>
<body>

    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>

    <div class="header-section">
        <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" alt="logo" width="60">
        <h1>SIHADIR MPP BKPSDM</h1>
        <p>Sistem Hadir Digital untuk Peserta Magang, PKL, dan Penelitian</p>
    </div>

    <div class="main-container">
        <div class="content-card">
            <h5 class="fw-bold mb-4">Input Absensi</h5>
            
            <form action="proses_absen.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Magang">Magang</option>
                        <option value="PKL">PKL</option>
                        <option value="Penelitian">Penelitian</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status_hadir" id="status_hadir" class="form-select" onchange="toggleForm()">
                        <option value="Hadir">Hadir ✅</option>
                        <option value="Izin">Izin 📩</option>
                        <option value="Sakit">Sakit 🏥</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="...">
                </div>

               <div class="mb-3">
    <label class="form-label">Foto</label>
  <div class="d-flex align-items-center gap-2 mb-2"> 
    <input type="file" name="file_surat" id="file_surat" class="form-control">
<button type="button" id="snap" class="btn btn-navy-custom text-nowrap">
        <i class="fas fa-camera"></i> Ambil Foto
    </button>       
 <button type="button" id="btnHapus" class="btn btn-navy-custom text-nowrap">
        <i class="fa-solid fa-trash"></i> Hapus Foto
    </button>
</div>
                     
                    <div class="text-center">
                        <video id="camera" autoplay playsinline></video>
                        <canvas id="canvas"></canvas>
                        <input type="hidden" name="foto_base64" id="foto_base64">
                    </div>
                </div>

                <div class="mb-3">
    <label class="form-label">Lokasi</label>
    <div class="d-flex align-items-center gap-2">
        <input type="text" id="display_lokasi" class="form-control" placeholder="Koordinat belum diambil" readonly>
        <button type="button" class="btn btn-navy-custom text-nowrap" onclick="getLocation()">
            <i class="fas fa-map-marker-alt"> 📍</i> Ambil Lokasi
        </button>
    </div>
    <input type="hidden" name="koordinat" id="koordinat">
    <input type="hidden" name="akurasi" id="akurasi">
</div>
                <button type="submit" class="btn btn-primary btn-simpan" ><span class="icon">💾</span><i class="fas fa-save me-2"></i> Simpan</button>
            </form>
        </div>

        <div class="main-container" style="max-width: 1300px;"> <div class="history-header mt-5">
        <div class="history-title">
            <img src="https://cdn-icons-png.flaticon.com/512/3502/3502688.png" alt="icon">
            <span>Riwayat Absensi</span>
        </div>
    </div>

    <div class="history-container" style="border-radius: 15px 15px;">
        <div class="table-responsive">
            <table class="table-siampp">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAMA</th>
                        <th>KATEGORI</th>
                        <th>STATUS</th>
                        <th>KETERANGAN</th>
                        <th>LOKASI</th>
                        <th>WAKTU</th>
                        <th>FOTO</th>
                        <th>STATUS AI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query mengambil data absensi
                $query = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($query)){
                    
                    // Logika Ikon AI untuk Keterlambatan
                    $ai_icon = (strpos($row['analisis_ai'], 'Terlambat') !== false) ? '⏰' : '✅';
                    $status_label = (strpos($row['analisis_ai'], 'Terlambat') !== false) ? 'Telat' : 'Tepat';
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['nama']; ?></strong></td>
                    <td><?php echo $row['kategori']; ?></td>
                    <td><?php echo $row['status_hadir']; ?></td>
                    <td><?php echo isset($row['keterangan']) ? $row['keterangan'] : '-'; ?></td>
                    <td>
                        <a href="https://www.google.com/maps?q=<?php echo $row['koordinat']; ?>" target="_blank" class="loc-link">
                            📍 <?php echo $row['koordinat']; ?> 
                            <br><small class="text-muted">(Akurasi ±<?php echo round($row['akurasi_meter']); ?>m)</small>
                        </a>
                    </td>
                    <td><?php echo date('d-m-Y H:i', strtotime($row['waktu_absen'])); ?></td>
                    <td>
                        <img src="uploads/<?php echo $row['foto']; ?>" width="45" height="35" style="object-fit: cover; border-radius: 5px;">
                    </td>
                    <td>
                        <div class="status-ai">
                            <span><?php echo $ai_icon; ?></span>
                            <span><?php echo $status_label; ?></span>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
 <footer class="text-center py-4">
            <h6 style="font-family: 'Poppins', sans-serif; color: #2106e6ff; font-weight: 700; letter-spacing: 0.5px;">
                Dibuat oleh Aulia Annisa (auliaannnnn_)
            </h6>
            <p style="color: #090142ff; font-size: 11px; opacity: 0.7; font-family: 'Inter', sans-serif;">
                SIHADIR MPP BKPSDM 
            </p>
        </footer>
    </div>

    <script>
    const video = document.getElementById('camera');
const canvas = document.getElementById('canvas');
const snap = document.getElementById('snap');
const btnHapus = document.getElementById('btnHapus');
const fotoBase64 = document.getElementById('foto_base64');
const fileInput = document.getElementById('file_surat');

let stream = null;

/* AKTIFKAN KAMERA */
navigator.mediaDevices.getUserMedia({ video: true })
    .then(s => {
        stream = s;
        video.srcObject = stream;
    })
    .catch(() => alert("Kamera tidak aktif"));

/* AMBIL FOTO */
snap.addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    fotoBase64.value = canvas.toDataURL('image/jpeg');

    canvas.style.display = 'block';
    video.style.display = 'none';
});

/* 🔥 HAPUS FOTO (INI YANG KAMU BUTUHKAN) */
btnHapus.addEventListener('click', () => {

    // Reset canvas
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    canvas.style.display = 'none';

    // Tampilkan kamera lagi
    video.style.display = 'block';

    // Kosongkan data
    fotoBase64.value = '';
    fileInput.value = '';

});
        function getLocation() {
            const display = document.getElementById('display_lokasi');
            display.value = "Mencari lokasi...";
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((p) => {
                    const lat = p.coords.latitude;
                    const lon = p.coords.longitude;
                    document.getElementById('koordinat').value = lat + "," + lon;
                    document.getElementById('akurasi').value = p.coords.accuracy;
                    display.value = lat + ", " + lon + " (Akurasi: " + p.coords.accuracy.toFixed(1) + "m)";
                }, (err) => {
                    display.value = "Gagal mengambil lokasi.";
                });
            }
        }
    </script>
</body>
</html>