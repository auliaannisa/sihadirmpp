<?php 
include 'config.php'; 
// Pastikan session dimulai untuk mengecek role admin
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); 
    exit();
}
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIHADIR MPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        body { background-color: #f1f2f5ab; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Header Biru Gradasi Berdasarkan image_138ee8.png */
        .header-blue {
            background: linear-gradient(135deg, #1f07faff 0%, #070136ff 100%);
            padding: 50px 0 100px;
            color: white;
            text-align: center;
            position: relative;
        }

        /* Tombol Logout Pojok Kanan Atas */
        .logout-link {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white !important;
            text-decoration: none;
            font-weight: bold;
            z-index: 1000;
        }

        .container-custom { max-width: 1300px; margin: -60px auto 50px; position: relative; z-index: 5; }

        /* Card untuk Tombol Refresh & Unduh */
        .action-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(17, 14, 168, 0.83);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(6, 16, 103, 0.86);
        }

        /* Badge Styling Berdasarkan image_8d1bcb.png */
        .badge-kategori { border: 1px solid #f8fafcff; color: #1f07faff; background: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-ai-warn { background: #fffdfdff; color: #1f07faff; border: 1px solid #02025c76; padding: 5px 10px; border-radius: 8px; font-weight: bold; font-size: 11px; }
        .badge-ai-success { background: #e8f5e9; color: #250af4ff; border: 1px solid #c8e6c9; padding: 5px 10px; border-radius: 8px; font-weight: bold; font-size: 11px; }

        .img-absensi { width: 50px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid #084bddff; }
    </style>
</head>
<body>

    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>

    <div class="header-blue">
        <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" width="50" class="mb-2">
        <h2 class="fw-bold">SIHADIR MPP BKPSDM</h2>
        <p class="opacity-75">Sistem Hadir Digital untuk Peserta Magang, PKL, dan Penelitian</p>
    </div>

    <div class="container container-custom">
        <div class="action-card">
            <h5 class="m-0 fw-bold text-dark"><i class="fas fa-list me-2"></i>Riwayat Kehadiran</h5>
            <div class="d-flex gap-2">
                <a href="admin.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
                <a href="ekspor_excel.php" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-file-excel"></i> Unduh Spreadsheet
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-secondary" style="font-size: 20px;">
                            <th>ID</th>
                            <th>NAMA</th>
                            <th>KATEGORI</th>
                            <th>STATUS AI</th>
                            <th>LOKASI & AKURASI</th>
                            <th>WAKTU</th>
                            <th class="text-center">FOTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                        if (!$sql) { die("Gagal Query: " . mysqli_error($conn)); }
                        
                        while($row = mysqli_fetch_array($sql)){
                            $analisis = $row['analisis_ai'] ?? 'N/A';
                            $is_late = (strpos(strtolower($analisis), 'terlambat') !== false);
                            $foto = $row['foto'];
                        ?>
                        <tr>
                            <td class="text-muted"><?= $row['id']; ?></td>
                            <td><strong><?= strtoupper($row['nama']); ?></strong></td>
                            <td><span class="badge-kategori"><?= strtoupper($row['kategori']); ?></span></td>
                            <td>
                                <span class="<?= $is_late ? 'badge-ai-warn' : 'badge-ai-success'; ?>">
                                    <?= $is_late ? '⚠️' : '✅'; ?> <?= $analisis; ?>
                                </span>
                            </td>
                            <td style="font-size: 11px;">
                                <i class="fas fa-map-marker-alt text-danger"></i> <?= $row['koordinat']; ?><br>
                                <span class="text-muted">Akurasi: <?= $row['akurasi_meter']; ?>m</span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= date('H:i', strtotime($row['waktu_absen'])); ?> WIB</div>
                                <div class="text-muted" style="font-size: 10px;"><?= date('d-m-Y', strtotime($row['waktu_absen'])); ?></div>
                            </td>
                            <td class="text-center">
                                <?php if(!empty($foto) && file_exists("uploads/$foto")): ?>
                                    <img src="uploads/<?= $foto; ?>" class="img-absensi" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted small">Tidak Ada</span>
                                <?php endif; ?>
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
</body>
</html>