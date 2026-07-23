<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit();
}

$id_tempat = $_GET['id'] ?? null;
if (!$id_tempat) {
    header("Location: beranda.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tempat_belajar WHERE id_tempat = :id");
$stmt->bindParam(':id', $id_tempat);
$stmt->execute();
$tempat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tempat) {
    echo "Tempat tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/reset1.css" />
	<link rel="stylesheet" href="../css/global1.css" />
	<link rel="stylesheet" href="../css/detail.css" />

</head>

<body>
	<div class="detail-tempat">
		<div class="detail-tempat-navbar">
			<div class="detail-tempat-container1">
				<img src="../assets/detail-tempat-wire-box1.png" class="detail-tempat-wire-box1" />
				<p class="detail-tempat-text1">Spotly.Kom</p>
			</div>
			
			<div class="detail-tempat-nav">
				<a href="beranda.php" class="detail-tempat-text-btn1" style="text-decoration: none;">Beranda</a>
				<a href="pengelolaan.php" class="detail-tempat-text-btn2" style="text-decoration: none;">Pengelolaan Tempat</a>
				<p class="detail-tempat-text-btn3">Profil</p>
			</div>
		</div>
		
		<div class="detail-tempat-main-content">
			<div class="detail-tempat-container2">
				<a href="beranda.php" class="detail-tempat-text2" style="text-decoration: none;">Beranda</a>
				<object data="../assets/detail-tempat-icon.svg" class="detail-tempat-icon" type="image/svg+xml"></object>
				<a href="pencarian.php" class="detail-tempat-text3" style="text-decoration: none;">Pencarian</a>
				<object data="../assets/detail-tempat-icon.svg" class="detail-tempat-icon" type="image/svg+xml"></object>
				<p class="detail-tempat-text4">Detail Tempat</p>
			</div>
			
			<div class="detail-tempat-container3">
				<section class="detail-tempat-container4">
					<div class="detail-tempat-photo-gallery">
						<div class="detail-tempat-wire-box2">[foto utama tempat]</div>
						
						<div class="detail-tempat-container5">
							<img src="../assets/detail-tempat-wire-box3.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box4.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box5.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box6.png" class="detail-tempat-wire-box" />
						</div>
					</div>
					
					<div class="detail-tempat-content-tabs">
						<div class="detail-tempat-container6">
							<p class="detail-tempat-text-btn4">Ulasan</p>
							<p class="detail-tempat-text-btn5">Laporan Kondisi</p>
						</div>
						
						<div class="detail-tempat-container7">
							<p class="detail-tempat-text-container">Ulasan Pengguna</p>
							
							<div class="detail-tempat-container8">
								<div class="detail-tempat-container9">
									<div class="detail-tempat-container10">
										<p class="detail-tempat-text5 text-dark">Arya K.</p>
										<p class="detail-tempat-text6">6 Des 2024</p>
									</div>
									
									<object data="../assets/detail-tempat-container1.svg" class="detail-tempat-container11" type="image/svg+xml"></object>
									<p class="detail-tempat-text-paragraph1 text-text">
										Perpustakaan yang sangat nyaman! Koleksi buku lengkap dan suasananya tenang banget.
									</p>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<div class="detail-tempat-place-info">
					<div class="detail-tempat-container12">
						<p class="detail-tempat-text-heading">Perpustakaan UNIKOM</p>
						
						<div class="detail-tempat-container13">
							<object data="../assets/detail-tempat-container2.svg" class="detail-tempat-container14" type="image/svg+xml"></object>
							<p class="detail-tempat-text7 text-dark">4.7</p>
							<p class="detail-tempat-text8">(156 ulasan)</p>
						</div>
					</div>
					
					<div class="detail-tempat-container15">
						<div class="detail-tempat-container16">
							<object data="../assets/detail-tempat-icon-margin.svg" class="detail-tempat-icon detail-tempat-icon-margin" type="image/svg+xml"></object>
							<p class="detail-tempat-text9">Jl. Dipatiukur, Kota Bandung, Jawa Barat</p>
						</div>
						
						<div class="detail-tempat-container17">
							<div class="detail-tempat-circle">
								<object data="../assets/detail-tempat-graphic.svg" class="detail-tempat-graphic" type="image/svg+xml"></object>
							</div>
							
							<p class="detail-tempat-text10">Jam Buka: 08.00 – 18.00 </p>
						</div>
					</div>
					
					<div class="detail-tempat-container18">
						<button class="btn-chip btn-chip1 hover-dark">
							<object data="../assets/btn-chip/btn-chip-icon1.svg" class="btn-chip-icon" type="image/svg+xml"></object>
							<p class="btn-chip-label"> WiFi</p>
						</button>
						
						<button class="btn-chip btn-chip2 hover-dark">
							<object data="../assets/btn-chip/btn-chip-icon2.svg" class="btn-chip-icon" type="image/svg+xml"></object>
							<p class="btn-chip-label"> Colokan</p>
						</button>
						
						<button class="btn-chip hover-dark">
							<object data="../assets/btn-chip/btn-chip-icon3.svg" class="btn-chip-icon" type="image/svg+xml"></object>
							<p class="btn-chip-label"> AC</p>
						</button>
					</div>
					
					<p class="text-text">
						Perpustakaan pusat yang menjadi favorit mahasiswa untuk belajar kelompok, akses jurnal, dan membaca. Dilengkapi dengan fasilitas lengkap dan suasana kondusif.
					</p>
					<button class="detail-tempat-btn hover-bright">Catat Kunjungan</button>
					
					<div class="card-margin">
						<p class="card-margin-text text-dark">Pengajuan data</p>
						
						<div class="container container1">
							<p class="container-text1">Nama</p>
							<p class="container-text2">Perpustakaan UNIKOM</p>
						</div>
						
						<div class="container">
							<p class="container-text1">Kategori Tempat</p>
							<p class="container-text2">Di kampus</p>
						</div>
						
						<div class="container">
							<p class="container-text1">Kategori Suasana</p>
							<p class="container-text2">Ramah Diskusi</p>
						</div>
						
						<div class="container">
							<p class="container-text1">Jam Buka</p>
							<p class="container-text2">08.00 – 18.00</p>
						</div>
						
						<div class="container container5">
							<p class="container-text1">Fasilitas</p>
							<p class="container-text2">WiFi, AC, Colokan</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>