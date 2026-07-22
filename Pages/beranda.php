<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit();
}

$nim  = $_SESSION['nim'];
$nama = $_SESSION['nama'];

$query = "SELECT tb.id_tempat, tb.nama_tempat, tb.foto, tb.fasilitas,
                 COUNT(DISTINCT k.id_kunjungan) AS jumlah_kunjungan,
                 ROUND(AVG(u.rating), 1) AS rata_rating
          FROM tempat_belajar tb
          LEFT JOIN kunjungan k ON tb.id_tempat = k.id_tempat
          LEFT JOIN ulasan u ON k.id_kunjungan = u.id_kunjungan
          WHERE tb.status_tempat = 'Disetujui'
          GROUP BY tb.id_tempat
          ORDER BY jumlah_kunjungan DESC, rata_rating DESC
          LIMIT 6";

$stmt = $pdo->prepare($query);
$stmt->execute();
$topRekomendasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
					<a href="beranda.php" class="navigation-text-btn3">Beranda</a>
					<a href = "pengelolaan_tempat.php" class="navigation-text-btn2">Pengelolaan Tempat</a>
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
							<div class="search-box">
								<form action="pencarian.php" method="GET" style="display: contents;">
									<input type="text" id="searchBeranda" class="container-text-input" placeholder="Cari tempat belajar...">
								</form>
							</div>
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
					
					<a href = "pencarian.php" class="container-btn">
						<button class="container-btn-lihat text2 hover-zoom">Lihat semua </button>
						<object data="../assets/assets1/col/container-button/container-icon.svg" class="icon container-icon2" type="image/svg+xml"></object>
					</a>
				</div>
				
				<div class="container-margin4" id="rekomendasiArea">
					<?php require __DIR__ . '/beranda_card.php'; ?>
				</div>
			</div>
		</div>
	</div>

<script>
const searchInput = document.getElementById('searchBeranda');
const rekomendasiArea = document.getElementById('rekomendasiArea');
let debounceTimer;

searchInput.addEventListener('input', () => {
	clearTimeout(debounceTimer);
	debounceTimer = setTimeout(() => {
		fetch('beranda_card.php?q=' + encodeURIComponent(searchInput.value))
			.then(res => res.text())
			.then(html => { rekomendasiArea.innerHTML = html; })
			.catch(err => console.error('Gagal memuat rekomendasi:', err));
	}, 300);
});
</script>
</body>

</html>