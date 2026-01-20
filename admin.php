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
        
        /* Logout Button */
        .logout-container { position: absolute; top: 25px; right: 30px; z-index: 1001; }
        .logout-link { 
            color: #ffffff !important; text-decoration: none !important; 
            font-weight: 600; font-size: 14px; padding: 8px 15px;
            border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; transition: 0.3s;
        }
        .logout-link:hover { background: rgba(255,255,255,0.1); border-color: #fff; }

        /* Header Area - Dibuat lebih rapat */
        .header-blue { 
            background: linear-gradient(135deg, #1f07fa 0%, #070136 100%); 
            padding: 40px 0 80px; color: white; text-align: center; position: relative; 
        }

        /* Container adjustment */
        .container-custom { max-width: 1400px; margin: -50px auto 50px; position: relative; z-index: 5; }

        /* Judul & Action Card */
        .action-card { 
            background: white; padding: 20px 25px; border-radius: 15px 15px 0 0; 
            border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;
        }
        .judul-riwayat { 
            background: linear-gradient(to right, #1f07fa, #031a51ff);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            font-weight: 800; margin: 0; font-size: 20px; text-transform: uppercase;
        }

        /* Table Styling Gradasi sesuai Gambar */
        .table-container-gradasi {
            background: white; border-radius: 0 0 15px 15px; 
            overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee;
        }
        .table-custom-header thead th {
            background: linear-gradient(to right, #1f07fa, #031a51);
            color: white !important; text-align: center; vertical-align: middle;
            font-weight: 700; font-size: 12px; padding: 15px 5px !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .table-custom-header tbody td {
            padding: 12px 10px !important; font-size: 13px;
            vertical-align: middle; border-bottom: 1px solid #f1f1f1;
        }

        /* Badge & Text Styles */
        .img-absensi { width: 55px; height: 40px; border-radius: 6px; object-fit: cover; cursor: pointer; transition: 0.3s; }
        .img-absensi:hover { transform: scale(1.1); }
        .text-keterangan-sub { font-size: 11px; font-style: italic; color: #666; display: block; margin-top: 2px; }
        .text-waktu-utama { color: #1f07fa; font-weight: 700; font-size: 14px; margin-bottom: 0; }
        
        /* Footer Action for Button Hapus */
        .footer-action {
            background: #f8f9fa; padding: 15px 25px; border-top: 1px solid #eee;
            display: flex; justify-content: space-between; align-items: center;
        }
   /* Gaya Badge Berwarna */
.badge-kat {
    font-size: 10px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    text-transform: uppercase;
    display: inline-block;
    width: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05);
}

/* Daftar Warna Kategori */
.kat-magang { background-color: #e3f2fd41; color: #0765fdff; }     /* Biru Muda */
.kat-pkl { background-color: #f1f8e980; color: #4cfa08ff; }        /* Hijau Muda */
.kat-penelitian { background-color: #fff3e049; color: #fb0909ff; } /* Oranye Muda */
.kat-default { background-color: #f5f5f5; color: #616161; }    /* Abu-abu */
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
        <p class="opacity-75">Sistem Hadir Digital Laporan Bulanan Peserta Magang & PKL</p>
    </div>

    <div class="container container-custom">
        <form action="hapus_masal.php" method="POST">
            
            <div class="action-card">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/3502/3502688.png" width="30" alt="">
                    <h2 class="judul-riwayat">Riwayat Absensi</h2>
                </div>
                <div class="d-flex gap-2">
                    <a href="export_spreadsheet.php" class="btn btn-success btn-sm fw-bold rounded-pill px-4">
                        <i class="fas fa-file-excel me-2"></i> Export Spreadsheet
                    </a>
                    <button type="submit" name="btn_hapus_masal" class="btn btn-danger btn-sm fw-bold px-4 rounded-pill shadow-sm">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Terpilih
                    </button>
                </div>
            </div>
            

           <div class="table-container-gradasi">
    <div class="table-responsive">
        <table class="table table-custom-header align-middle mb-0">
            <thead>
                <tr>
                    <th width="60"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                    <th width="200">NAMA LENGKAP</th>
                    <th width="130">KATEGORI</th>
                    <th width="100">STATUS</th>
                    <th width="150">STATUS AI</th>
                    <th width="180">KETERANGAN</th>
                    <th>LOKASI & AKURASI</th>
                    <th width="120">WAKTU</th>
                    <th width="80">FOTO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                while($row = mysqli_fetch_array($sql)){
                    $foto = $row['foto'];
                    $file_path = "uploads/" . $foto;
                    $is_late = (strpos(strtolower($row['analisis_ai'] ?? ''), 'terlambat') !== false);
                    
                    $status_class = 'bg-success';
                    if($row['status_hadir'] == 'Izin') $status_class = 'bg-warning text-dark';
                    if($row['status_hadir'] == 'Sakit') $status_class = 'bg-danger';
                ?>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="pilih[]" value="<?= $row['id']; ?>" class="checkItem form-check-input">
                        <div class="text-muted small mt-1" style="font-size: 9px;">ID: <?= $row['id']; ?></div>
                    </td>
                    
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 13px;"><?= strtoupper($row['nama']); ?></div>
                    </td>

                    <td class="text-center">
    <?php 
    // Logika penentuan warna berdasarkan teks kategori
    $kategori_label = $row['kategori'];
    $kat_class = 'kat-default'; // Warna cadangan

    switch (strtolower($kategori_label)) {
        case 'magang':
            $kat_class = 'kat-magang';
            break;
        case 'pkl':
            $kat_class = 'kat-pkl';
            break;
        case 'penelitian':
            $kat_class = 'kat-penelitian';
            break;
    }
    ?>
    <span class="badge-kat <?= $kat_class ?>">
        <?= $kategori_label ?>
    </span>
</td>

                    <td class="text-center">
                        <span class="badge <?= $status_class ?> shadow-sm w-100" style="font-size: 10px; padding: 5px;">
                            <?= $row['status_hadir']; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge <?= $is_late ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> border" style="font-size: 10px; padding: 5px;">
                            <?= $row['analisis_ai']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="text-keterangan-sub">
                            <?= (!empty($row['keterangan'])) ? $row['keterangan'] : '-'; ?>
                        </span>
                    </td>
                    <td>
                        <div style="font-size: 10px; line-height: 1.3;">
                            <strong class="text-danger">GPS:</strong> <?= $row['koordinat']; ?><br>
                            <strong class="text-primary">Acc:</strong> <?= $row['akurasi_meter']; ?>m
                        </div>
                    </td>
                    <td class="text-center">
                        <p class="text-waktu-utama" style="font-size: 13px;"><?= date('H:i', strtotime($row['waktu_absen'])); ?> WIB</p>
                        <small class="text-muted" style="font-size: 9px;"><?= date('d/m/y', strtotime($row['waktu_absen'])); ?></small>
                    </td>
                    <td class="text-center">
                        <?php if(!empty($foto) && file_exists($file_path)): ?>
                            <img src="<?= $file_path; ?>" class="img-absensi" style="width: 45px; height: 35px;" onclick="window.open(this.src)">
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="text-danger" onclick="return confirm('Hapus data ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

               

    <script>
        // Script Check All
        document.getElementById('checkAll').onclick = function() {
            var checkboxes = document.getElementsByClassName('checkItem');
            for (var checkbox of checkboxes) { 
                checkbox.checked = this.checked; 
            }
        }
    </script>
</body>
</html>