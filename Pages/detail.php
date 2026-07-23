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

// 1. Ambil data tempat
$stmt = $pdo->prepare("SELECT * FROM tempat_belajar WHERE id_tempat = :id");
$stmt->bindParam(':id', $id_tempat);
$stmt->execute();
$tempat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tempat) {
    echo "Tempat tidak ditemukan.";
    exit();
}

// 2. Hitung total ulasan & rata-rata rating (ulasan terhubung lewat kunjungan)
$stmtRating = $pdo->prepare("
    SELECT COUNT(*) AS total_ulasan, ROUND(AVG(u.rating), 1) AS rata_rating
    FROM ulasan u
    JOIN kunjungan k ON u.id_kunjungan = k.id_kunjungan
    WHERE k.id_tempat = :id
");
$stmtRating->bindParam(':id', $id_tempat);
$stmtRating->execute();
$ratingInfo = $stmtRating->fetch(PDO::FETCH_ASSOC);

// 3. Ambil daftar ulasan
$stmtUlasan = $pdo->prepare("
    SELECT u.rating, u.komentar, u.tgl_ulasan, m.nama_mahasiswa AS nama_mahasiswa
    FROM ulasan u
    JOIN kunjungan k ON u.id_kunjungan = k.id_kunjungan
    LEFT JOIN mahasiswa m ON k.nim = m.nim
    WHERE k.id_tempat = :id
    ORDER BY u.tgl_ulasan DESC
");
$stmtUlasan->bindParam(':id', $id_tempat);
$stmtUlasan->execute();
$daftarUlasan = $stmtUlasan->fetchAll(PDO::FETCH_ASSOC);

// 4. Ambil laporan kondisi terkini yang belum expired
$stmtKondisi = $pdo->prepare("
    SELECT *
    FROM laporan
    WHERE id_tempat = :id AND waktu_expired > NOW()
    ORDER BY waktu_expired DESC
");
$stmtKondisi->bindParam(':id', $id_tempat);
$stmtKondisi->execute();
$kondisiTerkini = $stmtKondisi->fetchAll(PDO::FETCH_ASSOC);

// Helper untuk cek file foto ada atau tidak
function fotoValid($namaFile) {
    if (empty($namaFile)) return false;
    return file_exists(__DIR__ . '/../assets/tempat/' . $namaFile);
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
				<p class="detail-tempat-text4"><?= htmlspecialchars($tempat['nama_tempat']) ?></p>
			</div>
			
			<div class="detail-tempat-container3">
				<section class="detail-tempat-container4">
					<div class="detail-tempat-photo-gallery">
						<div class="detail-tempat-wire-box2">
							<?php if (fotoValid($tempat['foto'])): ?>
								<img src="../assets/tempat/<?= htmlspecialchars($tempat['foto']) ?>"
								     alt="<?= htmlspecialchars($tempat['nama_tempat']) ?>"
								     style="width:100%; height:100%; object-fit:cover;" />
							<?php else: ?>
								<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#eee;color:#999;">
									[Foto belum tersedia]
								</div>
							<?php endif; ?>
						</div>
						
						<div class="detail-tempat-container5">
							<img src="../assets/detail-tempat-wire-box3.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box4.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box5.png" class="detail-tempat-wire-box" />
							<img src="../assets/detail-tempat-wire-box6.png" class="detail-tempat-wire-box" />
						</div>
					</div>
					
					<div class="detail-tempat-content-tabs">
						<div class="detail-tempat-container6">
							<p class="detail-tempat-text-btn4" onclick="tampilTab('ulasan')" style="cursor:pointer;">Ulasan</p>
							<p class="detail-tempat-text-btn5" onclick="tampilTab('kondisi')" style="cursor:pointer;">Laporan Kondisi</p>
						</div>
						
						<!-- TAB ULASAN -->
						<div class="detail-tempat-container7" id="tab-ulasan">
							<p class="detail-tempat-text-container">Ulasan Pengguna</p>
							
							<div class="detail-tempat-container8">
								<?php if (count($daftarUlasan) > 0): ?>
									<?php foreach ($daftarUlasan as $ulasan): ?>
										<div class="detail-tempat-container9">
											<div class="detail-tempat-container10">
												<p class="detail-tempat-text5 text-dark">
													<?= htmlspecialchars($ulasan['nama_mahasiswa'] ?? 'Pengguna') ?>
												</p>
												<p class="detail-tempat-text6">
													<?= date('d M Y', strtotime($ulasan['tgl_ulasan'])) ?>
												</p>
											</div>
											
											<object data="../assets/detail-tempat-container1.svg" class="detail-tempat-container11" type="image/svg+xml"></object>
											<p class="detail-tempat-text-paragraph1 text-text">
												<?= htmlspecialchars($ulasan['komentar']) ?>
											</p>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p class="text-text">Belum ada ulasan untuk tempat ini.</p>
								<?php endif; ?>
							</div>
						</div>
						
						<!-- TAB LAPORAN KONDISI -->
						<div class="detail-tempat-container7" id="tab-kondisi" style="display:none;">
							<p class="detail-tempat-text-container">Laporan Kondisi Terkini</p>
							
							<div class="detail-tempat-container8">
								<?php if (count($kondisiTerkini) > 0): ?>
									<?php foreach ($kondisiTerkini as $lapor): ?>
										<div class="detail-tempat-container9">
											<div class="detail-tempat-container10">
												<p class="detail-tempat-text5 text-dark">
													<?= htmlspecialchars($lapor['isi_laporan'] ?? $lapor['kondisi'] ?? '-') ?>
												</p>
												<p class="detail-tempat-text6">
													Berlaku sampai <?= date('d M Y H:i', strtotime($lapor['waktu_expired'])) ?>
												</p>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p class="text-text">Belum ada laporan kondisi terkini.</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</section>
				
				<div class="detail-tempat-place-info">
					<div class="detail-tempat-container12">
						<p class="detail-tempat-text-heading"><?= htmlspecialchars($tempat['nama_tempat']) ?></p>
						
						<div class="detail-tempat-container13">
							<object data="../assets/detail-tempat-container2.svg" class="detail-tempat-container14" type="image/svg+xml"></object>
							<p class="detail-tempat-text7 text-dark"><?= htmlspecialchars($ratingInfo['rata_rating'] ?? '0.0') ?></p>
							<p class="detail-tempat-text8">(<?= (int)($ratingInfo['total_ulasan'] ?? 0) ?> ulasan)</p>
						</div>
					</div>
					
					<div class="detail-tempat-container15">
						<div class="detail-tempat-container16">
							<object data="../assets/detail-tempat-icon-margin.svg" class="detail-tempat-icon detail-tempat-icon-margin" type="image/svg+xml"></object>
							<p class="detail-tempat-text9"><?= htmlspecialchars($tempat['lokasi']) ?></p>
						</div>
						
						<div class="detail-tempat-container17">
							<div class="detail-tempat-circle">
								<object data="../assets/detail-tempat-graphic.svg" class="detail-tempat-graphic" type="image/svg+xml"></object>
							</div>
							
							<p class="detail-tempat-text10">Jam Buka: <?= htmlspecialchars($tempat['jam_buka']) ?></p>
						</div>
					</div>
					
					<div class="detail-tempat-container18">
						<?php
						$fasilitas = array_map('trim', explode(',', $tempat['fasilitas']));
						$iconMap = [
							'wifi'    => 'btn-chip-icon1.svg',
							'colokan' => 'btn-chip-icon2.svg',
							'ac'      => 'btn-chip-icon3.svg',
						];
						foreach ($fasilitas as $f):
							if ($f === '') continue;
							$key  = strtolower($f);
							$icon = $iconMap[$key] ?? 'btn-chip-icon1.svg';
						?>
							<button class="btn-chip hover-dark">
								<object data="../assets/btn-chip/<?= $icon ?>" class="btn-chip-icon" type="image/svg+xml"></object>
								<p class="btn-chip-label"> <?= htmlspecialchars($f) ?></p>
							</button>
						<?php endforeach; ?>
					</div>
					
					<p class="text-text">
						<?= nl2br(htmlspecialchars($tempat['deskripsi'])) ?>
					</p>
					<button class="detail-tempat-btn hover-bright">Catat Kunjungan</button>
					
					<div class="card-margin">
						<p class="card-margin-text text-dark">Pengajuan data</p>
						
						<div class="container container1">
							<p class="container-text1">Nama</p>
							<p class="container-text2"><?= htmlspecialchars($tempat['nama_tempat']) ?></p>
						</div>
						
						<div class="container">
							<p class="container-text1">Kategori Tempat</p>
							<p class="container-text2"><?= htmlspecialchars($tempat['kategori_tempat']) ?></p>
						</div>
						
						<div class="container">
							<p class="container-text1">Kategori Suasana</p>
							<p class="container-text2"><?= htmlspecialchars($tempat['kategori_suasana']) ?></p>
						</div>
						
						<div class="container">
							<p class="container-text1">Jam Buka</p>
							<p class="container-text2"><?= htmlspecialchars($tempat['jam_buka']) ?></p>
						</div>
						
						<div class="container container5">
							<p class="container-text1">Fasilitas</p>
							<p class="container-text2"><?= htmlspecialchars($tempat['fasilitas']) ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
function tampilTab(nama) {
	document.getElementById('tab-ulasan').style.display  = (nama === 'ulasan')  ? 'block' : 'none';
	document.getElementById('tab-kondisi').style.display  = (nama === 'kondisi') ? 'block' : 'none';
}
</script>
</body>

</html>