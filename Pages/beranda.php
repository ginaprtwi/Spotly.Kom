<?php
session_start();

if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit();
}

$nim  = $_SESSION['nim'];
$nama = $_SESSION['nama'];
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/css1/reset.css" />
	<link rel="stylesheet" href="../css/css1/global.css" />
	<link rel="stylesheet" href="../css/css1/col2.css" />

</head>

<body>
	<div class="container3">
		<header class="header">
			<div class="header-container1">
				<div class="header-container2">
					<img src="../assets/assets1/container/container-wire-box.png" class="wire-box-a header-wire-box" />
					<p class="header-text">Spotly.Kom</p>
				</div>
				
				<div class="navigation nav">
					<p class="text-button navigation-text-btn1">Beranda  </p>
					<p class="navigation-text-btn2">Pengelolaan Tempat</p>
					<a href="profil.php" class="navigation-text-btn3">Profil</a>
				</div>
			</div>
		</header>
		
		<div class="container-section1">
			<div class="container-margin2 section">
				<div class="container-container2">
					<div class="container-container3">
						<div class="container-container4">
							<h2 class="subtitle-primary">Halo, <?= htmlspecialchars($nama) ?>!</h2>
							<p class="container-text-paragraph2 text-text">
								Temukan tempat belajar terbaik di sekitarmu<br />
								dan belajar lebih nyaman hari ini.
							</p>
						</div>
						
						<div class="container-margin3">
							<object data="../assets/assets1/col/container-icon.svg" class="container-icon4" type="image/svg+xml"></object>
							<p class="container-text-input">Cari tempat belajar...</p>
						</div>
					</div>
					
					<img src="../assets/assets1/col/container-tabloid1.png" class="container-tabloid1" />
				</div>
			</div>
			
			<img src="../assets/assets1/col/container-tabloid2.png" class="container-tabloid2 input" />
		</div>
		
		<div class="container-section-margin1 section">
			<div class="container-section2">
				<div class="container-c container4">
					<p class="container-text3">Rekomendasi untukmu</p>
					
					<div class="container-btn">
						<button class="container-btn-lihat text2 hover-zoom">Lihat semua </button>
						<object data="../assets/assets1/col/container-button/container-icon.svg" class="icon container-icon2" type="image/svg+xml"></object>
					</div>
				</div>
				
				<div class="container-margin4">
					<div class="card card1">
						<img src="../assets/assets1/col/card/card-img.png" class="card-img" />
						
						<div class="card-container1">
							<p class="text-dark">Perpustakaan Pusat UNIKOM</p>
							
							<div class="card-container2">
								<button class="btn-text card-btn-text1 hover-dark">Colokan</button>
								<button class="btn-text card-btn-text2 hover-dark">Tenang</button>
								<button class="btn-text card-btn-text3 hover-dark">AC</button>
							</div>
							
							<div class="card-container3">
								<object data="../assets/assets1/col/card-container/card-icon2.svg" class="card-icon" type="image/svg+xml"></object>
								<p class="card-text">4.2</p>
							</div>
						</div>
					</div>
					
					<div class="card card2">
						<img src="../assets/assets1/col/card/card-img.png" class="card-img" />
						
						<div class="card-container1">
							<p class="text-dark">Perpustakaan Pusat UNIKOM</p>
							
							<div class="card-container2">
								<button class="btn-text card-btn-text1 hover-dark">Colokan</button>
								<button class="btn-text card-btn-text2 hover-dark">Tenang</button>
								<button class="btn-text card-btn-text3 hover-dark">AC</button>
							</div>
							
							<div class="card-container3">
								<object data="../assets/assets1/col/card-container/card-icon.svg" class="card-icon" type="image/svg+xml"></object>
								<p class="card-text">4.2</p>
							</div>
						</div>
					</div>
					
					<div class="card card3">
						<img src="../assets/assets1/col/card/card-img.png" class="card-img" />
						
						<div class="card-container1">
							<p class="text-dark">Perpustakaan Pusat UNIKOM</p>
							
							<div class="card-container2">
								<button class="btn-text card-btn-text1 hover-dark">Colokan</button>
								<button class="btn-text card-btn-text2 hover-dark">Tenang</button>
								<button class="btn-text card-btn-text3 hover-dark">AC</button>
							</div>
							
							<div class="card-container3">
								<object data="../assets/assets1/col/card-container/card-icon.svg" class="card-icon" type="image/svg+xml"></object>
								<p class="card-text">4.2</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container-section-margin2 section">
			<div class="container-section3">
				<div class="container-c container5">
					<p class="container-text3">Tempat Populer</p>
					
					<div class="container-btn">
						<button class="container-btn-lihat text2 hover-zoom">Lihat semua </button>
						<object data="../assets/assets1/col/container-button/container-icon.svg" class="icon container-icon2" type="image/svg+xml"></object>
					</div>
				</div>
				
				<div class="container-margin5 card-white2">
					<img src="../assets/assets1/col/container-container.png" class="container-container" />
					
					<div class="container-d container6">
						<p class="container-text-paragraph text1">Jl. Gufuwara Hartu No. 3, Malang</p>
						
						<div class="container-container1">
							<div class="container-circle">
								<object data="../assets/assets1/col/container-circle-icon/container-graphic.svg" class="container-graphic" type="image/svg+xml"></object>
							</div>
							
							<p class="container-text-buka">Buka 08.00 – 23.00</p>
						</div>
					</div>
					
					<div class="container-e container7">
						<object data="../assets/assets1/col/card-container/card-icon2.svg" class="icon container-icon3" type="image/svg+xml"></object>
						<p class="container-text4">123</p>
					</div>
				</div>
				
				<div class="container-margin6 card-white2">
					<img src="../assets/assets1/col/container-container.png" class="container-container" />
					
					<div class="container-d container8">
						<p class="container-text-paragraph text1">Jl. Gufuwara Hartu No. 3, Malang</p>
						
						<div class="container-container1">
							<div class="container-circle">
								<object data="../assets/assets1/col/container-circle-icon/container-graphic.svg" class="container-graphic" type="image/svg+xml"></object>
							</div>
							
							<p class="container-text-buka">Buka 08.00 – 23.00</p>
						</div>
					</div>
					
					<div class="container-e container9">
						<object data="../assets/assets1/col/card-container/card-icon2.svg" class="icon container-icon3" type="image/svg+xml"></object>
						<p class="container-text4">123</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<img src="../assets/container-container.png" class="img" />
</body>

</html>