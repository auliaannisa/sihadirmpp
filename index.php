<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIHADIR MPP BKPSDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e4e8f5ff; /* baground*/
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .login-card {
            background-color: #1b05e4ff; /* Biru card sedikit lebih terang */
            padding: 40px 30px;
            border-radius: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: white;
            box-shadow: 0 0 50px rgba(3, 6, 65, 0.9); /* Efek bayangan dalam */
            border: 1px solid rgba(50, 30, 232, 0.88);
        }

        .login-card img {
            width: 80px;
            margin-bottom: 20px;
        }

        .login-card h2 {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .login-card p {
            font-size: 0.85rem;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: none;
        }

        .btn-login {
            background-color: white;
            color: blue;
            font-weight: 700;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
        }

        /* Footer teks di bawah */
        .footer-text {
            margin-top: 20px;
            color: #010854fb;
            font-size: 1rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" alt="Logo">
    
    <h2>SIHADIR MPP BKPSDM</h2>
    <p>Sistem Hadir Digital untuk Peserta Magang, PKL, <br> dan Penelitian</p>

    <?php
    if(isset($_POST['login'])){
        $u = mysqli_real_escape_string($conn, $_POST['username']);
        $p = $_POST['password'];
        $role = $_POST['role'];
        
        $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$u' AND password='$p' AND role='$role'");
        
        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: " . ($row['role'] == 'admin' ? 'admin.php' : 'dashboard.php'));
            exit();
        } else {
            echo "<div class='alert alert-danger py-2 small mb-3'>Login gagal, cek kembali data Anda.</div>";
        }
    }
    ?>

    <form action="" method="POST">
        <select name="role" class="form-select" required>
            <option value="" selected disabled>Login sebagai</option>
            <option value="peserta">Peserta</option>
            <option value="admin">Admin</option>
        </select>
        
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        
        <button type="submit" name="login" class="btn btn-login">Login</button>
    </form>
</div>

<div class="footer-text">
    SIHADIR MPP Dibuat oleh Aulia Annisa
</div>

</body>
</html>