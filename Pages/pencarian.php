<?php
require_once __DIR__ . '\koneksi.php';

// 1. Capture filter inputs from GET request
$search           = $_GET['q'] ?? '';
$kategori_tempat  = $_GET['kategori_tempat'] ?? '';
$kategori_suasana = $_GET['kategori_suasana'] ?? '';
$fasilitas        = $_GET['fasilitas'] ?? []; // Array of checked facilities
$sort             = $_GET['sort'] ?? '';

// 2. Base Query
$sql = "SELECT * FROM tempat_belajar WHERE status_tempat = 'Disetujui'";
$params = [];

// Filter: Search Keyword (Name or Location)
if (!empty($search)) {
    $sql .= " AND (nama_tempat LIKE :search OR lokasi LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

// Filter: Kategori Tempat
if (!empty($kategori_tempat)) {
    $sql .= " AND kategori_tempat = :kategori_tempat";
    $params[':kategori_tempat'] = $kategori_tempat;
}

// Filter: Kategori Suasana
if (!empty($kategori_suasana)) {
    $sql .= " AND kategori_suasana = :kategori_suasana";
    $params[':kategori_suasana'] = $kategori_suasana;
}

// Filter: Fasilitas (Matches any checked facility in comma-separated column)
if (!empty($fasilitas) && is_array($fasilitas)) {
    $fasilitas_conditions = [];
    foreach ($fasilitas as $index => $fasi) {
        $param_name = ":fasilitas_" . $index;
        $fasilitas_conditions[] = "fasilitas LIKE " . $param_name;
        $params[$param_name] = '%' . $fasi . '%';
    }
    $sql .= " AND (" . implode(" OR ", $fasilitas_conditions) . ")";
}

// Filter: Sorting
if ($sort === 'nama_asc') {
    $sql .= " ORDER BY nama_tempat ASC";
} elseif ($sort === 'nama_desc') {
    $sql .= " ORDER BY nama_tempat DESC";
} else {
    $sql .= " ORDER BY id_tempat DESC"; // Default
}

try {
    // 3. Execute Prepared Statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $places = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_places = count($places);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/css1/reset.css" />
	<link rel="stylesheet" href="../css/css1/global.css" />
	<link rel="stylesheet" href="../css/css1/col1.css" />

</head>

<body>
	<div class="pencarian">
		<div class="pencarian-body-a">
			<div class="pencarian-app">
				<div class="pencarian-navbar">
					<div class="container-a container3">
						<img src="../assets/assets1/container/container-wire-box.png" class="wire-box-a container-wire-box" />
						<p class="container-text1">Spotly.Kom</p>
					</div>
					
					<div class="navigation nav">
						<a href = "beranda.php" p class="text-button navigation-text-btn1">Beranda  </p>
						<a href = "pengelolaan_tempat.php" p class="navigation-text-btn2">Pengelolaan Tempat</p>
						<a href = "profil.php" class="navigation-text-btn3">Profil</a>
					</div>
				</div>
				
				<div class="pencarian-container1">
					<p class="pencarian-text1">Pencarian</p>
					<p class="pencarian-text2">Ba</p>
					<button class="pencarian-btn-text1 hover-zoom text1">Beranda</button>
					<object data="../assets/assets/col/pencarian-icon1.svg" class="pencarian-icon1" type="image/svg+xml"></object>
					<button class="pencarian-btn-text2 text1 hover-zoom">Pencarian</button>
				</div>
				
				<div class="pencarian-main-content">
					<div class="pencarian-section">
						<div class="pencarian-container2">
							<div class="pencarian-container3">
								<img src="../assets/assets1/col/pencarian-tabloid1.png" class="pencarian-tabloid1" />
								<h2 class="pencarian-subtitle">Pencarian Tempat Belajar</h2>
								<p class="pencarian-text-paragraph1 text-text">
									Temukan tempat belajar terbaik di sekitarmu<br />
									dan belajar lebih nyaman hari ini.
								</p>
							</div>
							
							<img src="../assets/assets1/col/pencarian-tabloid2.png" class="pencarian-tabloid2 input" />
						</div>
					</div>
					
					<div class="pencarian-container4">
						<form method="GET" action="pencarian.php" class="pencarian-filter-sidebar">
							<!-- Search Bar -->
							<div class="btn1 hover-dark" style="display: flex; align-items: center; padding: 8px 12px; gap: 8px;">
								<object data="../assets/assets1/col/btn-icon1.svg" class="btn-icon1" type="image/svg+xml"></object>
								<input type="text" name="q" value="<?= htmlspecialchars($search); ?>" placeholder="Cari tempat belajar..." style="border: none; background: transparent; outline: none; width: 100%; font-family: inherit;">
							</div>
							
							<!-- Kategori Tempat -->
							<div class="check-group check-group1">
								<p class="check-group-text">Kategori Tempat</p>
								<div class="check-group-container">
									<label class="label-a label1" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
										<input type="radio" name="kategori_tempat" value="Di kampus" <?= $kategori_tempat === 'Di kampus' ? 'checked' : ''; ?>>
										<span class="label-text">Di Kampus</span>
									</label>
									
									<label class="label-a label2" style="cursor: pointer; display: flex; align-items: center; gap: 8px; margin-top: 6px;">
										<input type="radio" name="kategori_tempat" value="Sekitar Kampus" <?= $kategori_tempat === 'Sekitar Kampus' ? 'checked' : ''; ?>>
										<span class="label-text">Sekitar Kampus</span>
									</label>
								</div>
							</div>
							
							<!-- Suasana -->
							<div class="check-group check-group2">
								<p class="check-group-text">Suasana</p>
								<div class="check-group-container">
									<label class="label-a label1" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
										<input type="radio" name="kategori_suasana" value="Tenang" <?= $kategori_suasana === 'Tenang' ? 'checked' : ''; ?>>
										<span class="label-text">Tenang (Privat)</span>
									</label>
									
									<label class="label-a label2" style="cursor: pointer; display: flex; align-items: center; gap: 8px; margin-top: 6px;">
										<input type="radio" name="kategori_suasana" value="Ramah Diskusi" <?= $kategori_suasana === 'Ramah Diskusi' ? 'checked' : ''; ?>>
										<span class="label-text">Ramah diskusi</span>
									</label>
								</div>
							</div>
							
							<!-- Fasilitas -->
							<div class="pencarian-check-group">
								<p class="pencarian-text-paragraph2">Fasilitas</p>
								<div class="pencarian-container6" style="display: flex; flex-direction: column; gap: 6px;">
									<?php 
										$options_fasilitas = ['Colokan', 'AC', 'WiFi', 'Mushola', 'Parkir'];
										foreach ($options_fasilitas as $f): 
											$isChecked = in_array($f, $fasilitas) ? 'checked' : '';
									?>
										<label class="label-a" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
											<input type="checkbox" name="fasilitas[]" value="<?= $f; ?>" <?= $isChecked; ?>>
											<span class="label-text"><?= $f; ?></span>
										</label>
									<?php endforeach; ?>
								</div>
							</div>
							
							<!-- Urutkan -->
							<div class="pencarian-container8">
								<p class="pencarian-text-paragraph3">Urutkan</p>
								<div class="pencarian-filter-select">
									<select name="sort" style="border: none; background: transparent; outline: none; width: 100%; font-family: inherit; cursor: pointer;">
										<option value="">Terbaru</option>
										<option value="nama_asc" <?= $sort === 'nama_asc' ? 'selected' : ''; ?>>Nama (A-Z)</option>
										<option value="nama_desc" <?= $sort === 'nama_desc' ? 'selected' : ''; ?>>Nama (Z-A)</option>
									</select>
								</div>
							</div>
							
							<!-- Action Buttons -->
							<div class="pencarian-container9" style="display: flex; gap: 8px; margin-top: 16px;">
								<button type="submit" class="pencarian-btn hover-bright text2" style="cursor: pointer;">Terapkan Filter</button>
								<a href="pencarian.php" class="btn2 hover-zoom" style="display: flex; align-items: center; justify-content: center; text-decoration: none; padding: 8px 12px;">
									<span class="btn-label2">Reset</span>
								</a>
							</div>
						</form>
						
						<div class="pencarian-results-area">
							<p class="pencarian-text-container">
								<b class="bold-primary"><?= htmlspecialchars($total_places); ?> </b>Tempat ditemukan
							</p>

							<div class="pencarian-grid-wrapper" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
								<?php foreach ($places as $row): ?>
									<?php 
										// Resolve image path
										$image_src = (!empty($row['foto']) && file_exists(__DIR__ . '/../' . $row['foto'])) 
													? '../' . htmlspecialchars($row['foto']) 
													: '../assets/assets1/col/card-place/card-place-wire-box.png';

										// Parse comma-separated facilities
										$fasilitas_list = !empty($row['fasilitas']) ? explode(',', $row['fasilitas']) : [];
									?>

									<div class="card-place-b card-place2" style="margin-bottom: 15px;">
										<img src="<?= $image_src; ?>" class="wire-box-b card-place-wire-box2" alt="<?= htmlspecialchars($row['nama_tempat']); ?>" style="object-fit: cover;" />
										
										<div class="card-place-container2">
											<p class="text-dark"><?= htmlspecialchars($row['nama_tempat']); ?></p>
											
											<div class="container-b container2">
												<object data="../assets/assets1/col/container/container-icon.svg" class="container-icon1" type="image/svg+xml"></object>
												<p class="container-text2">4.5</p>
											</div>
											
											<p class="card-place-text-paragraph2"><?= htmlspecialchars($row['lokasi']); ?></p>
											
											<div class="card-place-container3" style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px;">
												<button class="btn-tag card-place-btn-tag1 hover-dark">
													<?= htmlspecialchars($row['kategori_suasana']); ?>
												</button>
												
												<?php foreach ($fasilitas_list as $fasi): ?>
													<button class="btn-tag card-place-btn-tag2 hover-dark">
														<?= htmlspecialchars(trim($fasi)); ?>
													</button>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>