<?php 
include 'config.php'; 
date_default_timezone_set('Asia/Jakarta');

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SIHADIR MPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        body { background-color: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .admin-header { background: #1e3c72; padding: 40px 20px; color: white; text-align: center; }
        .content-container { max-width: 1400px; margin: 20px auto; padding: 0 20px; }
        
        .top-actions { 
            background: white; padding: 20px; border-radius: 15px; 
            margin-bottom: 20px; display: flex; justify-content: space-between; 
            align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .table-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        
        /* Badge Warna Kategori */
        .badge-kategori { padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 10px; border: 1px solid rgba(0,0,0,0.1); }
        .kat-magang { background-color: #e0f2fe; color: #0369a1; } /* Biru Muda */
        .kat-pkl { background-color: #071c631a; color: #0c28a9d9; }    /* biru */
        .kat-penelitian { background-color: #dcfce7; color: #15803d; } /* Hijau */
        .kat-li { background-color: #2530a3ff; color: #102af0ff; }    /* Ungu */
        .kat-default { background-color: #f3f4f6; color: #374151; }

        .badge-ai { padding: 5px 10px; border-radius: 6px; font-weight: bold; font-size: 11px; }
        .bg-warning-custom { background: #fee2e2; color: #024368ff; }
        .bg-success-custom { background: #dcfce7; color: #15803d; }
    </style>
</head>
<body>

<div class="admin-header">
    <h2 class="fw-bold">SIHADIR MPP BKPSDM</h2>
    <p>Monitoring Absensi Digital</p>
</div>

<div class="content-container">
    <div class="top-actions">
        <h5 class="m-0 fw-bold"><i class="fas fa-users me-2"></i> Data Kehadiran</h5>
        <div class="d-flex gap-2">
            <a href="admin.php" class="btn btn-outline-primary fw-bold">Segarkan</a>
            <a href="ekspor_excel.php" class="btn btn-success fw-bold">Unduh Spreadsheet</a>
            <a href="logout.php" class="btn btn-danger fw-bold">Keluar</a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>NAMA</th>
                        <th>KATEGORI</th>
                        <th>STATUS AI</th>
                        <th>LOKASI</th>
                        <th>WAKTU</th>
                        <th>FOTO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                    while($row = mysqli_fetch_array($sql)){
                        // Logika Warna Kategori
                        $kategori = $row['kategori'];
                        $class_kat = "kat-default";
                        if(strtolower($kategori) == 'magang') $class_kat = "kat-magang";
                        elseif(strtolower($kategori) == 'pkl') $class_kat = "kat-pkl";
                        elseif(strtolower($kategori) == 'penelitian') $class_kat = "kat-penelitian";
                        elseif(strtolower($kategori) == 'li') $class_kat = "kat-li";

                        $analisis = $row['analisis_ai'] ?? '-';
                        $is_bad = (strpos(strtolower($analisis), 'terlambat') !== false);
                    ?>
                    <tr>
                        <td class="text-center"><?= $row['id']; ?></td>
                        <td><strong><?= strtoupper($row['nama']); ?></strong></td>
                        <td>
                            <span class="badge-kategori <?= $class_kat; ?>">
                                <?= strtoupper($kategori); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-ai <?= $is_bad ? 'bg-warning-custom' : 'bg-success-custom'; ?>">
                                <?= $analisis; ?>
                            </span>
                        </td>
                        <td style="font-size: 11px;">
                            <i class="fas fa-map-marker-alt text-danger"></i> <?= $row['koordinat']; ?>
                        </td>
                        <td><?= date('H:i d/m/Y', strtotime($row['waktu_absen'])); ?></td>
                        <td>
                            <?php if(!empty($row['foto'])): ?>
                                <img src="uploads/<?= $row['foto']; ?>" style="width:50px; border-radius:5px;">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>