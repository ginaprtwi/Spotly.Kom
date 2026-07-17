<?php
session_start();
require_once 'koneksi.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $nim = trim($_POST['nim']);
    $password = trim($_POST['password']);

    if (!empty($nim) && !empty($password)) {
        try {
            // Securely look for the user using a prepared statement
            $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
            $stmt->execute(['nim' => $nim]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($password === $user['password']) {
                    $_SESSION['nim'] = $user['nim'];
                    $_SESSION['nama'] = $user['nama_mahasiswa'];   
                    // Redirect to your homepage/dashboard
                    header("Location: beranda.php"); 
                    exit;
                } else {
                    $error_message = "Password yang Anda masukkan salah.";
                }
            } else {
                $error_message = "NIM tidak terdaftar.";
            }
        } catch (PDOException $e) {
            $error_message = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    } else {
        $error_message = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/masuk.css" />
    <title>Masuk - Spotly.Kom</title>
</head>

<body>
    <header class="header">
        <div class="header-container1">
            <div class="container-a container1">
                <img src="../assets/container/container-wire-box.png" class="container-wire-box" />
                <p class="container-text1">Spotly.Kom</p>
            </div>
            
            <div class="header-container2">
                <button class="header-btn1 text-white btn hover-bright">Masuk</button>
                <a href="daftar.php" class="header-btn2 text-white btn-white hover-dark" style="text-decoration: none; display: inline-block; text-align: center;">Daftar</a>
            </div>
        </div>
    </header>
    
    <div class="main-margin section">
        <div class="main-margin-main">
            <div class="main-margin-container1">
                <div class="card-landscape-placeholder">
                    <div class="card-landscape-placeholder-container"></div>
                    <img src="../assets/masuk/card-landscape-placeholder-icon.png" class="card-landscape-placeholder-icon" />
                </div>
                
                <div class="main-margin-container2">
                    <p class="main-margin-text-heading">
                        Temukan tempat belajar terbaik<br />
                        di sekitar UNIKOM
                    </p>
                    <p class="main-margin-text-paragraph">
                        Akses rekomendasi fasilitas, suasana, dan kondisi terkini serta baca ulasan dari mahasiswa lainnya.
                    </p>
                </div>
            </div>
            
            <form action="" method="POST" class="card-login-form">
                <p class="card-login-form-text-heading text-primary">Masuk ke Spotly.Kom</p>
                
                <!-- Display error alert if login fails -->
                <?php if (!empty($error_message)): ?>
                    <p style="color: red; font-size: 14px; margin-bottom: 10px; font-weight: bold;">
                        <?php echo $error_message; ?>
                    </p>
                <?php endif; ?>

                <p class="card-login-form-text-paragraph text">
                    Masuk untuk mengakses rekomendasi tempat belajar,<br />
                    ulasan mahasiswa, dan fitur exclusif lainnya.
                </p>
                
                <div class="input-group-margin input-group-container">
                    <p class="input-group-margin-text">NIM<span class="sub-text-red">*</span></p>
                    <input type="text" name="nim" class="input-group-margin-container" placeholder="Masukkan NIM Anda" required />
                </div>
                
                <div class="margin container-margin">
                    <p class="margin-text-label">Password<span class="sub-text-red">*</span></p>
                    
                    <div class="margin-container" style="display: flex; align-items: center;">
                        <input type="password" name="password" class="margin-text-password" placeholder="Masukkan password Anda" required style="border: none; background: transparent; width: 100%; outline: none;" />
                        <img src="../assets/margin-container-margin/margin-btn.png" class="margin-btn" />
                    </div>
                </div>

                <button type="submit" name="login_submit" class="card-login-form-btn text-white hover-bright">Masuk</button>
                <p class="card-login-form-text-container text-gray">
					Belum punya akun? <a href="daftar.php" class="bold">Daftar</a>
				</p>
            </form>
        </div>
    </div>
</body>
</html>