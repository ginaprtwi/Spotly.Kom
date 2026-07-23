<?php
session_start(); 
require 'koneksi.php';
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama_mahasiswa']);
    $nim = trim($_POST['nim']);
    $email = trim($_POST['email_unikom']);
    $password = $_POST['password'];

    if (empty($nama) || empty($nim) || empty($email) || empty($password)) {
        $error = "Semua field wajib diisi!";
    } elseif (!str_ends_with($email, "@mahasiswa.unikom.ac.id")) {
        $error = "Email tidak valid! Harap gunakan email resmi UNIKOM.";
    } else {
        $stmt = $pdo->prepare("SELECT nim FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);

        if ($stmt->rowCount() > 0) {
               $error = "NIM sudah terdaftar! Silakan login.";
		} else {
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama_mahasiswa, email_unikom, password) VALUES (?, ?, ?, ?)");
			$stmt->execute([$nim, $nama, $email, $hashedPassword]);

			$_SESSION['nim']  = $nim;
			$_SESSION['nama'] = $nama;

			header("Location: beranda.php");
			exit;
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/reset.css" />
	<link rel="stylesheet" href="../css/global.css" />
	<link rel="stylesheet" href="../css/daftar.css" />

</head>

<body>
	<div class="body-a">
		<header class="header">
			<div class="header-container1">
				<div class="container-a container1">
					<img src="../assets/container/container-wire-box.png" class="container-wire-box" />
					<p class="container-text1">Spotly.Kom</p>
				</div>
				
				<div class="header-container2">
					<a href="masuk.php" class="header-btn1 hover-dark text-white btn-white">Masuk</a>
  					<a href="daftar.php" class="header-btn2 text-white hover-bright btn">Daftar</a>
				</div>
			</div>
		</header>
		
		<div class="body-a-main-content1 section">
			<div class="body-a-main-content2">
				<div class="card-register-form">
					<div class="card-register-form-container1">
						<p class="text-primary">Buat Akun Spotly.Kom</p>
						<p class="card-register-form-text-paragraph">
							Daftar sekarang untuk mendapatkan rekomendasi tempat belajar terbaik<br />
							dan terhubung dengan komunitas UNIKOM lainnya.
						</p>
					</div>
				
				<?php if ($error): ?>
					<p class="input-group-margin" style="color:red;"><?= $error ?></p>
				<?php endif; ?>
				<?php if ($success): ?>
					<p class="input-group-margin" style="color:green;"><?= $success ?></p>
				<?php endif; ?>	

				<form method="POST", action="daftar.php">	

					<div class="input-group-margin">
						<p class="input-group-margin-text">Nama Lengkap <span class="sub-text-red">*</span></p>
						<input class="input-group-margin-container" type="text" name="nama_mahasiswa" value="<?= htmlspecialchars($_POST['nama_mahasiswa'] ?? '') ?>" placeholder="Masukkan nama lengkap" />
					</div>
					
					<div class="input-group-margin">
						<p class="input-group-margin-text">NIM <span class="sub-text-red">*</span></p>
						<input class="input-group-margin-container" type="text" name="nim" value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>" placeholder="Masukkan NIM Anda" />
					</div>
					
					<div class="input-group-margin">
						<p class="input-group-margin-text">Email UNIKOM <span class="sub-text-red">*</span></p>
						<input class="input-group-margin-container" type="email" name="email_unikom" value="<?= htmlspecialchars($_POST['email_unikom'] ?? '') ?>" placeholder="namadepan.nim@mahasiswa.unikom.ac.id" />
					</div>
					
					<div class="margin field-margin">
						<p class="margin-text-label">Password <span class="sub-text-red">*</span></p>
						<div class="margin-container">
							<input class="margin-text-password" type="password" name="password" id="password-input" placeholder="Masukkan password Anda">
							<img src="../assets/margin-container-margin/margin-btn.png" class="margin-btn" id="toggle-password" style="cursor: pointer;"/>
						</div>
					</div>

					
					<div class="card-register-form-label">
					</div>
					<button type="submit" style="width: 500px; height: 30px; text-align: center;" class="card-register-form-container2 text-white">Daftar</button>
					<p class="card-register-form-text-container text-gray">
						Sudah punya akun? <a href="masuk.php" class="bold-primary">Masuk</a>
					</p>
					</div>
				</form>
				<div class="body-a-illustration">
					<div class="body-a-container1">
						<div class="body-a-container2">
							<img src="../assets/daftar/body-a-wire-box1.png" class="body-a-wire-box body-a-wire-box1" />
							<img src="../assets/daftar/body-a-wire-box2.png" class="wire-box body-a-wire-box2" />
						</div>
						
						<img src="../assets/daftar/body-a-wire-box3.png" class="wire-box body-a-wire-box3" />
						
						<div class="body-a-container3">
							<img src="../assets/daftar/body-a-wire-box4.png" class="body-a-wire-box" />
							<img src="../assets/daftar/body-a-wire-box5.png" class="body-a-wire-box" />
							<img src="../assets/daftar/body-a-wire-box6.png" class="body-a-wire-box" />
						</div>
					</div>
					
					<div class="body-a-container4">
						<p class="body-a-text-heading">Temukan. Bandingkan. Belajar lebih baik.</p>
						<p class="body-a-text-paragraph">
							Dapatkan informasi lengkap mengenai fasilitas, suasana, dan kondisi semua tempat belajar di sekitar kampusmu.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		const toggleBtn = document.getElementById('toggle-password');
		const passwordInput = document.getElementById('password-input');

		toggleBtn.addEventListener('click', function () {
			if (passwordInput.type === 'password') {
			passwordInput.type = 'text';
			} else {
			passwordInput.type = 'password';
			}
		});
	</script>
</body>

</html>