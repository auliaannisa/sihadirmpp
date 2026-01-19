<?php 
include 'config.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); 
    exit();
}
date_default_timezone_set('Asia/Jakarta');

function is_image($filename) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
    return in_array($ext, $allowed);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIHADIR MPP BKPSDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .logout-container { position: absolute; top: 25px; right: 30px; z-index: 1001; }
        .logout-link { 
            color: #ffffff !important; 
            text-decoration: none !important; 
            font-weight: 600; 
            font-size: 14px;
            letter-spacing: 0.5px;
            transition: 0.3s;
            padding: 8px 15px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
        }
        .logout-link:hover { background: rgba(255,255,255,0.1); border-color: #fff; }
        .header-blue { 
            background: linear-gradient(135deg, #1f07fa 0%, #070136 100%); 
            padding: 70px 0 110px; color: white; text-align: center; position: relative; 
        }
        .container-custom { max-width: 1400px; margin: -70px auto 50px; position: relative; z-index: 5; }
        .action-card { 
            background: white; padding: 25px; border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(18, 6, 88, 0.94); margin-bottom: 25px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .judul-riwayat { 
            background: linear-gradient(to right, #1f07fa, #031a51ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800; 
            margin: 0; 
            font-size: 24px; 
            text-transform: uppercase;
        }
        .btn-group-custom { display: flex; gap: 12px; }
        .img-absensi { width: 60px; height: 45px; border-radius: 8px; object-fit: cover; border: 1px solid #eee; transition: 0.3s; cursor: pointer; }
        .img-absensi:hover { transform: scale(1.1); box-shadow: 0 5px 15px rgba(219, 14, 14, 0.93); }
        .table { background: white; border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #f1f4f9; color: #070136; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        .text-lokasi { font-size: 11px; color: #072aecff; line-height: 1.4; }
        
        /* Tambahan Style untuk Keterangan */
        .text-keterangan { font-size: 11px; font-style: italic; color: #666; display: block; margin-top: 4px; max-width: 180px; }
    </style>
</head>
<body>

    <div class="logout-container">
        <a href="logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt me-2"></i> KELUAR SISTEM
        </a>
    </div>

    <div class="header-blue">
        <h1 class="fw-bold">SIHADIR MPP BKPSDM</h1>
        <p class="opacity-75">Sistem Hadir Digital untuk Peserta Magang, PKL dan Penelitian</p>
    </div>

    <div class="container container-custom">
        <form action="hapus_masal.php" method="POST">
            
            <div class="action-card">
                <h2 class="judul-riwayat">Riwayat Absensi</h2>
                <div class="btn-group-custom">
                    <button type="submit" name="btn_hapus_masal" class="btn btn-danger btn-sm fw-bold px-4 rounded-pill shadow-sm" onclick="return confirm('Hapus data terpilih?')">
                        <i class="fas fa-trash-alt me-1"></i> Hapus Terpilih
                    </button>
                    <a href="ekspor_excel.php" class="btn btn-success btn-sm fw-bold px-4 rounded-pill shadow-sm">
                        <i class="fas fa-file-excel me-1"></i> Ekspor Excel
                    </a>
                </div>
            </div>

            <div class="bg-white p-2 rounded-4 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="50" class="text-center"><input type="checkbox" id="checkAll"></th>
                                <th>NAMA & KATEGORI</th>
                                <th>STATUS & KETERANGAN</th> <th>ANALISIS AI</th>
                                <th>LOKASI & AKURASI</th> 
                                <th>WAKTU PRESENSI</th>
                                <th class="text-center">DOKUMEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                            while($row = mysqli_fetch_array($sql)){
                                $foto = $row['foto'];
                                $file_path = "uploads/" . $foto;
                                $is_late = (strpos(strtolower($row['analisis_ai']), 'terlambat') !== false);
                                
                                // Penentuan warna badge status hadir
                                $status_class = 'bg-success';
                                if($row['status_hadir'] == 'Izin') $status_class = 'bg-warning text-dark';
                                if($row['status_hadir'] == 'Sakit') $status_class = 'bg-danger';
                            ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" name="pilih[]" value="<?= $row['id']; ?>" class="checkItem"></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= strtoupper($row['nama']); ?></div>
                                    <span class="badge bg-light text-secondary border" style="font-size: 10px;"><?= $row['kategori']; ?></span>
                                </td>
                                
                                <td>
                                    <span class="badge <?= $status_class ?> shadow-sm" style="font-size: 11px;">
                                        <?= $row['status_hadir']; ?>
                                    </span>
                                    <span class="text-keterangan">
                                        <i class="fas fa-info-circle me-1"></i> <?= (!empty($row['keterangan'])) ? $row['keterangan'] : '-'; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= $is_late ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> p-2 shadow-sm" style="font-size: 11px;">
                                        <i class="fas <?= $is_late ? 'fa-clock' : 'fa-check-circle'; ?> me-1"></i> <?= $row['analisis_ai']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-lokasi">
                                        <strong><i class="fas fa-map-marker-alt text-danger"></i> Koordinat:</strong> <?= $row['koordinat']; ?><br>
                                        <strong><i class="fas fa-bullseye text-primary"></i> Akurasi:</strong> <?= $row['akurasi_meter']; ?> meter
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary"><?= date('H:i', strtotime($row['waktu_absen'])); ?> WIB</div>
                                    <small class="text-muted"><?= date('d M Y', strtotime($row['waktu_absen'])); ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if(!empty($foto) && file_exists($file_path)): ?>
                                        <?php if(is_image($foto)): ?>
                                            <img src="<?= $file_path; ?>" class="img-absensi" onclick="window.open(this.src)">
                                        <?php else: ?>
                                            <a href="<?= $file_path; ?>" target="_blank" class="btn btn-outline-danger btn-sm fw-bold" style="font-size: 10px;">
                                                <i class="fas fa-file-pdf"></i> LIHAT PDF
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('checkAll').onclick = function() {
            var checkboxes = document.getElementsByClassName('checkItem');
            for (var checkbox of checkboxes) { checkbox.checked = this.checked; }
        }
    </script>
</body>
</html>