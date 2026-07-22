<?php
session_start();
require_once __DIR__ . '\koneksi.php';

$current_nim = $_SESSION['nim'] ?? '';

// Query places with aggregated Upvotes and Downvotes
$sql = "SELECT 
            t.*,
            SUM(CASE WHEN v.jenis_vote = 'UPVOTE' THEN 1 ELSE 0 END) AS total_upvotes,
            SUM(CASE WHEN v.jenis_vote = 'DOWNVOTE' THEN 1 ELSE 0 END) AS total_downvotes,
            MAX(CASE WHEN v.nim = :nim THEN v.jenis_vote ELSE NULL END) AS user_vote
        FROM tempat_belajar t
        LEFT JOIN vote v ON t.id_tempat = v.id_tempat
        GROUP BY t.id_tempat
        ORDER BY FIELD(t.status_tempat, 'Diajukan', 'Disetujui'), t.tgl_submit DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':nim' => $current_nim]);
$tempat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Chunk place list into groups of 3 to match the exact row layout structure in col3.css
$tempat_chunks = array_chunk($tempat_list, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Pengelolaan Tempat - Spotly.Kom</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/css1/reset.css" />
	<link rel="stylesheet" href="../css/css1/global.css" />
	<link rel="stylesheet" href="../css/css1/col3.css" />
</head>

<body>
	<div class="pengelolaan-tempat">
		<div class="pengelolaan-tempat-body-a">
			
			<!-- Navigation Header -->
			<div class="pengelolaan-tempat-header">
				<div class="container-a container3">
					<img src="../assets/assets1/container/container-wire-box.png" class="wire-box-a container-wire-box" alt="Logo Icon" />
					<p class="container-text1">Spotly.Kom</p>
				</div>
				
				<div class="pengelolaan-tempat-nav">
					<a href="pencarian.php" style="text-decoration:none;"><p class="pengelolaan-tempat-text-btn1">Beranda</p></a>
					<a href="pengelolaan_tempat.php" style="text-decoration:none;"><p class="text-button pengelolaan-tempat-text-btn2">Pengelolaan Tempat</p></a>
					<a href="profil.php" style="text-decoration:none;"><p class="pengelolaan-tempat-text-btn3">Profil</p></a>
				</div>
			</div>
			
			<!-- Hero CTA Banner -->
			<div class="pengelolaan-tempat-section1">
				<div class="pengelolaan-tempat-container1">
					<div class="pengelolaan-tempat-container2">
						<div class="pengelolaan-tempat-container3">
							<h2 class="pengelolaan-tempat-subtitle subtitle-primary">Pengelolaan Tempat</h2>
							<p class="pengelolaan-tempat-text-paragraph1 text-text">
								Temukan tempat belajar terbaik di sekitarmu dan belajar lebih nyaman hari ini.
							</p>
						</div>
						
						<div class="pengelolaan-tempat-ctabanner">
							<object data="../assets/assets1/col/pengelolaan-tempat-container.svg" class="pengelolaan-tempat-container4" type="image/svg+xml"></object>
							
							<div class="pengelolaan-tempat-container5">
								<p class="pengelolaan-tempat-text-paragraph2">Punya rekomendasi tempat baru?</p>
								<p class="pengelolaan-tempat-text-paragraph3 text1">
									Bagikan tempat favoritmu kepada sesama mahasiswa bersama.
								</p>
							</div>
							
							<a href="tambah-tempat.php" style="text-decoration:none;">
								<button class="pengelolaan-tempat-btn hover-bright" type="button">Tambah Tempat</button>
							</a>
						</div>
						
						<img src="../assets/assets1/col/pengelolaan-tempat-tabloid1.png" class="pengelolaan-tempat-tabloid1 input" alt="Illustration 1" />
					</div>
					
					<img src="../assets/assets1/col/pengelolaan-tempat-tabloid2.png" class="pengelolaan-tempat-tabloid2" alt="Illustration 2" />
				</div>
			</div>
			
			<!-- Dynamic Places Cards Grid -->
			<div class="pengelolaan-tempat-section-margin">
				<div class="pengelolaan-tempat-section2">
					<p class="pengelolaan-tempat-text-container">Vote Tempat</p>
					
					<?php if (empty($tempat_list)): ?>
						<p style="padding: 20px 0; color: #666;">Belum ada tempat belajar yang terdaftar.</p>
					<?php else: ?>

						<?php foreach ($tempat_chunks as $chunkIndex => $chunk): ?>
							<!-- Container row layout selection based on chunk index -->
							<div class="<?= $chunkIndex === 0 ? 'pengelolaan-tempat-container6' : 'pengelolaan-tempat-section3'; ?>">
								
								<?php foreach ($chunk as $itemIndex => $row): ?>
									<?php 
										// Extract image path
										$imagePath = !empty($row['foto']) ? '../' . htmlspecialchars($row['foto']) : '../assets/assets1/col/card-img.png';
										
										// Parse badges (fasilitas + suasana)
										$tags = [];
										if (!empty($row['fasilitas'])) {
											$tags = array_merge($tags, array_map('trim', explode(',', $row['fasilitas'])));
										}
										if (!empty($row['kategori_suasana'])) {
											$tags[] = $row['kategori_suasana'];
										}
										$tags = array_unique(array_filter($tags));
									?>

									<!-- Individual Place Card -->
									<div class="card1 card-light">
										<!-- Image sits on top in normal flex flow -->
										<img src="<?= $imagePath; ?>" class="card-img2" alt="<?= htmlspecialchars($row['nama_tempat']); ?>" />
										
										<!-- Card Content Container -->
										<div class="card-container5">
											<p class="text-dark"><?= htmlspecialchars($row['nama_tempat']); ?></p>
											
											<!-- Badges -->
											<div class="card-container6">
												<?php foreach (array_slice($tags, 0, 3) as $tag): ?>
													<button type="button" class="btn-text container-btn-text1 hover-dark">
														<?= htmlspecialchars($tag); ?>
													</button>
												<?php endforeach; ?>
											</div>
											
											<!-- Votes / Action Row -->
											<div class="row row-bottom2">
												<p class="row-text">
													<?= (int)$row['total_upvotes']; ?> Disetujui | <?= (int)$row['total_downvotes']; ?> Tidak Setuju
												</p>

												<!-- Upvote -->
												<form action="vote.php" method="POST" style="display:inline; margin:0;">
													<input type="hidden" name="id_tempat" value="<?= $row['id_tempat']; ?>">
													<input type="hidden" name="jenis_vote" value="UPVOTE">
													<input type="hidden" name="redirect" value="pengelolaan_tempat.php">
													<button type="submit" style="background:none; border:none; padding:0; cursor:pointer;" title="Upvote">
														<object data="../assets/assets1/col/row/row-mdi-like<?= $row['user_vote'] === 'UPVOTE' ? '11' : ''; ?>.svg" class="row-mdi-like row-mdi-like1" type="image/svg+xml" style="pointer-events:none;"></object>
													</button>
												</form>

												<!-- Downvote -->
												<form action="vote.php" method="POST" style="display:inline; margin:0;">
													<input type="hidden" name="id_tempat" value="<?= $row['id_tempat']; ?>">
													<input type="hidden" name="jenis_vote" value="DOWNVOTE">
													<input type="hidden" name="redirect" value="pengelolaan_tempat.php">
													<button type="submit" style="background:none; border:none; padding:0; cursor:pointer;" title="Downvote">
														<object data="../assets/assets1/col/row/row-mdi-like<?= $row['user_vote'] === 'DOWNVOTE' ? '12' : '2'; ?>.svg" class="row-mdi-like row-mdi-like2" type="image/svg+xml" style="pointer-events:none;"></object>
													</button>
												</form>
											</div>
										</div>
									</div>

								<?php endforeach; ?>

							</div>
						<?php endforeach; ?>

					<?php endif; ?>

				</div>
			</div>

		</div>
	</div>
</body>
</html>