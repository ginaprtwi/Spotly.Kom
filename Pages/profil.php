<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit();
}

$nim = $_SESSION['nim'];

$stmt = $pdo->prepare("SELECT nim, nama_mahasiswa, email_unikom FROM mahasiswa WHERE nim = :nim");
$stmt->bindParam(':nim', $nim);
$stmt->execute();
$data_mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

$nama          = $data_mahasiswa['nama_mahasiswa'];
$email_unikom  = $data_mahasiswa['email_unikom'];

$riwayatKunjungan = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Riwayat Ulasan 
$query = "SELECT tb.nama_tempat, u.komentar, u.rating, u.tgl_ulasan
          FROM ulasan u
          JOIN kunjungan k ON u.id_kunjungan = k.id_kunjungan
          JOIN tempat_belajar tb ON k.id_tempat = tb.id_tempat
          WHERE k.nim = :nim
          ORDER BY u.tgl_ulasan DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':nim', $nim);
$stmt->execute();

$riwayatUlasan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Riwayat Pengajuan 
$query = "SELECT nama_tempat, status_tempat, tgl_submit
          FROM tempat_belajar
          WHERE nim = :nim
          ORDER BY tgl_submit DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':nim', $nim);
$stmt->execute();

$riwayatPengajuan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung jumlah kunjungan
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM kunjungan WHERE nim = :nim");
$stmt->bindParam(':nim', $nim);
$stmt->execute();
$total_kunjungan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Hitung jumlah ulasan 
$stmt = $pdo->prepare("SELECT COUNT(*) AS total 
                        FROM ulasan u
                        JOIN kunjungan k ON u.id_kunjungan = k.id_kunjungan
                        WHERE k.nim = :nim");
$stmt->bindParam(':nim', $nim);
$stmt->execute();
$total_ulasan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Hitung jumlah tempat yang diajukan
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tempat_belajar WHERE nim = :nim");
$stmt->bindParam(':nim', $nim);
$stmt->execute();
$total_pengajuan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/css2/reset.css" />
	<link rel="stylesheet" href="../css/css2/global.css" />
	<link rel="stylesheet" href="../css/css2/profil.css" />

</head>

<body>
	<div class="body-a">
		<header class="header header1">
			<div class="header-container1">
				<div class="header-container2">
					<img src="../assets/assets2/header-container/header-wire-box.png" class="wire-box header-wire-box" />
					<p class="header-text">Spotly.Kom</p>
				</div>
				
				<div class="header-nav">
					<a href="beranda.php" class="navigation-text-btn3">Beranda</a>
					<a href = "pengelolaan_tempat.php" class="navigation-text-btn2">Pengelolaan Tempat</a>
					<a href="profil.php" class="navigation-text-btn3">Profil</a>
				</div>
			</div>
		</header>
		
		<div class="body-a-main-content1 section">
			<div class="body-a-main-content2">
				<div class="container-a container3">
					<p class="container-text-heading">Profil Saya</p>
					<p class="container-text-paragraph1">
						Kelola informasi profil dan lihat aktivitasmu di Spotly.Kom
					</p>
				</div>
				
				<div class="body-a-container1">
					<div class="body-a-sidebar">
						<div class="card1">
							<div class="card-container3">
								<p class="card-text-paragraph1 text3"><?= htmlspecialchars($nama) ?></p>
								<p class="card-text-paragraph2 text-border">NIM: <?= htmlspecialchars($nim) ?></p>
								<p class="card-text-paragraph3"><?= htmlspecialchars($email_unikom) ?></p>
							</div>
							
							<div class="card-container4">
								<div class="container-d container4">
									<p class="container-text-paragraph4"><?= $total_kunjungan ?></p>
									<p class="container-text-paragraph5">Kunjungan</p>
								</div>

								<div class="container-d container5">
									<p class="container-text-paragraph4"><?= $total_ulasan ?></p>
									<p class="container-text-paragraph5">Ulasan</p>
								</div>

								<div class="container-d container6">
									<p class="container-text-paragraph4"><?= $total_pengajuan ?></p>
									<p class="container-text-paragraph5">
										Tempat <br />
										Diajukan
									</p>
								</div>
							</div>
						</div>
						
						<a href="keluar.php" class="btn-margin1 hover-bright">
    						<object data="../assets/assets2/profil/btn-margin-icon.svg" class="btn-margin-icon" type="image/svg+xml"></object>
    						<p class="btn-margin-label">Keluar</p>
						</a>
					</div>
					
					<div class="body-a-container2">
						<div class="body-a-container3">
							<p class="body-a-text-btn1 tab-btn active" data-tab="kunjungan">Riwayat Kunjungan</p>
   							<p class="body-a-text-btn2 tab-btn" data-tab="ulasan">Riwayat Ulasan</p>
    						<p class="body-a-text-btn3 tab-btn" data-tab="pengajuan">Riwayat Pengajuan Tempat</p>
						</div>
						
						<div class="body-a-container4 tab-content" id="tab-kunjungan">
    						<p class="body-a-text-container text3">Riwayat Kunjungan</p>
    						<div class="body-a-container5">
								<?php if (count($riwayatKunjungan) > 0): ?>
									<?php foreach ($riwayatKunjungan as $data): ?>
										<div class="card card3">
											<img src="../sassets/assets2/profil/card/card-container.png" class="container-e card-container1" />
											<div class="card-container2">
												<p class="card-text text5"><?= htmlspecialchars($data['nama_tempat']) ?></p>
												<div class="container-f container2">
													<p class="container-text-paragraph6"><?= date('d M Y', strtotime($data['tgl_kunjungan'])) ?></p>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p>Belum ada riwayat kunjungan</p>
								<?php endif; ?>
							</div>
						</div>

						<div class="body-a-container4 tab-content" id="tab-ulasan" style="display: none;">
							<p class="body-a-text-container text3">Riwayat Ulasan</p>

							<div class="body-a-container5">
								<?php if (count($riwayatUlasan) > 0): ?>
									<?php foreach ($riwayatUlasan as $data): ?>
										<div class="card card3">
											<div class="card-container2">
												<p class="card-text text5"><?= htmlspecialchars($data['nama_tempat']) ?></p>
												<p><?= htmlspecialchars($data['komentar']) ?></p>
												<div class="container-f container2">
													<p class="container-text-paragraph6">Rating: <?= htmlspecialchars($data['rating']) ?>/5</p>
													<p class="container-text-paragraph7"><?= date('d M Y', strtotime($data['tgl_ulasan'])) ?></p>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p>Belum ada riwayat ulasan</p>
								<?php endif; ?>
							</div>
						</div>

						<div class="body-a-container4 tab-content" id="tab-pengajuan" style="display: none;">
							<p class="body-a-text-container text3">Riwayat Pengajuan Tempat</p>

							<div class="body-a-container5">
								<?php if (count($riwayatPengajuan) > 0): ?>
									<?php foreach ($riwayatPengajuan as $data): ?>
										<div class="card card3">
											<div class="card-container2">
												<p class="card-text text5"><?= htmlspecialchars($data['nama_tempat']) ?></p>
												<div class="container-f container2">
													<p class="container-text-paragraph6">Status: <?= htmlspecialchars($data['status_tempat']) ?></p>
													<p class="container-text-paragraph7"><?= date('d M Y', strtotime($data['tgl_submit'])) ?></p>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p>Belum ada riwayat pengajuan</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
document.querySelectorAll('.tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        this.classList.add('active');
        var target = this.getAttribute('data-tab');
        document.getElementById('tab-' + target).style.display = 'block';
    });
});
</script>

</body>

</html>