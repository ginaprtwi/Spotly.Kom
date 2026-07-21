<?php
session_start(); // 1. Start session to access $_SESSION['nim']
require_once __DIR__ . '\koneksi.php';

// 2. Auth Guard: Ensure the user is logged in
if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit;
}

// Get logged-in user's NIM from session
$nim = $_SESSION['nim'];

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 3. Capture inputs
    $nama_tempat      = trim($_POST['nama_tempat'] ?? '');
    $lokasi           = trim($_POST['lokasi'] ?? '');
    $jam_buka         = !empty($_POST['jam_buka']) ? $_POST['jam_buka'] : null;
    $jam_tutup        = !empty($_POST['jam_tutup']) ? $_POST['jam_tutup'] : null;
    $deskripsi        = trim($_POST['deskripsi'] ?? '');
    $kategori_tempat  = $_POST['kategori_tempat'] ?? '';
    $kategori_suasana = $_POST['kategori_suasana'] ?? '';
    
    $fasilitas_arr    = $_POST['fasilitas'] ?? [];
    $fasilitas        = !empty($fasilitas_arr) ? implode(', ', $fasilitas_arr) : null;

    $status_tempat    = 'Diajukan';
    $tgl_submit       = date('Y-m-d');

    $foto_path = null;

    // Handle File Upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['foto']['tmp_name'];
        $fileName      = $_FILES['foto']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = 'tempat_' . uniqid() . '.' . $fileExtension;
            $uploadDir   = __DIR__ . '/../uploads/tempat/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $foto_path = 'uploads/tempat/' . $newFileName;
            }
        }
    }

    // Insert into DB using $nim from session
    try {
        $sql = "INSERT INTO tempat_belajar 
            (nama_tempat, kategori_tempat, lokasi, fasilitas, kategori_suasana, foto, deskripsi, jam_buka, jam_tutup, status_tempat, tgl_submit, nim) 
            VALUES 
            (:nama_tempat, :kategori_tempat, :lokasi, :fasilitas, :kategori_suasana, :foto, :deskripsi, :jam_buka, :jam_tutup, :status_tempat, :tgl_submit, :nim)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama_tempat'      => $nama_tempat,
            ':kategori_tempat'  => $kategori_tempat,
            ':lokasi'           => $lokasi,
            ':fasilitas'        => $fasilitas,
            ':kategori_suasana' => $kategori_suasana,
            ':foto'             => $foto_path,
            ':deskripsi'        => $deskripsi,
            ':jam_buka'         => $jam_buka,
            ':jam_tutup'        => $jam_tutup,
            ':status_tempat'    => $status_tempat,
            ':tgl_submit'       => $tgl_submit,
            ':nim'              => $nim // Valid foreign key from session!
        ]);

        $message = "Pengajuan tempat berhasil dikirim!";
        $message_type = "success";
    } catch (PDOException $e) {
        $message = "Gagal menyimpan data: " . $e->getMessage();
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/css2/reset.css" />
  <link rel="stylesheet" href="../css/css2/global.css" />
  <link rel="stylesheet" href="../css/css2/tambah-tempat.css" />
</head>

<body>
	<header class="header header1">
		<div class="header-container1">
			<div class="header-container2">
				<img src="../assets/assets2/header-container/header-wire-box.png" class="wire-box header-wire-box" />
				<p class="header-text">Spotly.Kom</p>
			</div>
			
			<div class="header-nav">
				<a href="pencarian.php" class="header-text-btn1" style="text-decoration:none; color:inherit;">Beranda</a>
				<p class="header-text-btn2">Pengelolaan Tempat</p>
				<p class="header-text-btn3">Profil</p>
			</div>
		</div>
	</header>
	
	<div class="tambah-tempat-body-a section">
		<div class="tambah-tempat-main-content">
			<div class="container-a container3">
				<p class="container-text-heading">Tambah Tempat Belajar</p>
				<p class="container-text-paragraph1">
					Bagikan tempat belajar favoritmu kepada komunitas. Lengkapi informasi berikut agar mudah ditemukan.
				</p>
			</div>

			<!-- Status Alert Message -->
			<?php if (!empty($message)): ?>
				<div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 8px; <?= $message_type === 'success' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
					<?= htmlspecialchars($message); ?>
				</div>
			<?php endif; ?>
			
			<form method="POST" action="tambah-tempat.php" enctype="multipart/form-data" class="tambah-tempat-container1">
				<div class="tambah-tempat-form-section">
					
					<!-- Foto Upload -->
					<div class="tambah-tempat-container2">
						<p class="tambah-tempat-text-label1">Foto Tempat</p>
						
						<div class="card-photo-upload-margin" style="display:flex; flex-direction:column; gap:12px; align-items:flex-start;">
							<div class="card-photo-upload-margin-container">
								<p class="card-photo-upload-margin-text-paragraph1 text1">Unggah foto tempat atau tampilan tempat</p>
								<p class="text-border">Format JPG/PNG, maks. 1 Foto</p>
							</div>
							
							<input type="file" name="foto" accept="image/*" required style="font-family: inherit;">
						</div>
					</div>
					
					<!-- Nama Tempat -->
					<div class="tambah-tempat-container3">
						<p class="text-text1">Nama Tempat</p>
						<input type="text" name="nama_tempat" placeholder="Perpustakaan Pusat UNIKOM" required class="card-white1" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px; font-family: inherit;">
					</div>

					<!-- NIM (Mahasiswa) - Locked to Session Value -->
					<div class="tambah-tempat-container3" style="margin-top: 15px;">
						<p class="text-text1">NIM Mahasiswa</p>
						<input 
							type="text" 
							name="nim" 
							value="<?= htmlspecialchars($_SESSION['nim'] ?? ''); ?>" 
							readonly 
							required 
							class="card-white1" 
							style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px; font-family: inherit; background-color: #e9ecef; cursor: not-allowed;"
						>
					</div>
					
					<!-- Lokasi -->
					<div class="tambah-tempat-container4">
						<p class="text-text1">Lokasi</p>
						<input type="text" name="lokasi" placeholder="Jl. Dipati Ukur No. 112, Bandung" required class="card-white1" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px; font-family: inherit;">
					</div>
					
					<!-- Jam Operasional -->
					<div class="tambah-tempat-container6">
						<div class="label-a label1">
							<p class="label-text-jam label-text">Jam Buka</p>
							<p class="label-text-jam label-text-jam-tutup">Jam Tutup</p>
						</div>
						
						<div class="tambah-tempat-container7" style="display: flex; gap: 16px;">
							<input type="time" name="jam_buka" class="card-white2" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">
							<input type="time" name="jam_tutup" class="card-white2" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">
						</div>
					</div>
					
					<!-- Deskripsi -->
					<div class="input-group-container">
						<p class="text-text1">Deskripsi Singkat</p>
						<textarea name="deskripsi" placeholder="Deskripsikan pengalaman belajar di tempat tersebut" class="input-group-container-input card-white1" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px; font-family: inherit; resize: vertical;"></textarea>
					</div>
					
					<!-- Kategori Dropdowns -->
					<div class="tambah-tempat-container8">
						<div class="label-a label2">
							<p class="label-text-jam label-text">Kategori Tempat</p>
							<p class="label-text-jam label-text-jam-tutup">Kategori Suasana</p>
						</div>
						
						<div class="tambah-tempat-container9" style="display: flex; gap: 16px;">
							<select name="kategori_tempat" required style="width: 50%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
								<option value="">Pilih Kategori Tempat</option>
								<option value="Di kampus">Di kampus</option>
								<option value="Sekitar Kampus">Sekitar Kampus</option>
							</select>

							<select name="kategori_suasana" required style="width: 50%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
								<option value="">Pilih Kategori Suasana</option>
								<option value="Tenang">Tenang</option>
								<option value="Ramah Diskusi">Ramah Diskusi</option>
							</select>
						</div>
					</div>
					
					<!-- Fasilitas Checklist -->
					<div class="tambah-tempat-container10">
						<p class="tambah-tempat-text-label4">Fasilitas yang Tersedia</p>
						
						<div class="tambah-tempat-facilities" style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px;">
							<?php 
								$available_fasilitas = ['WiFi', 'Colokan', 'AC', 'Mushola', 'Toilet', 'Parkir'];
								foreach ($available_fasilitas as $fasi):
							?>
								<label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 12px; background: #f0f0f0; border-radius: 6px;">
									<input type="checkbox" name="fasilitas[]" value="<?= $fasi; ?>">
									<span><?= $fasi; ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					
					<!-- Submit Button -->
					<button type="submit" class="tambah-tempat-container11 text-text1" style="border: none; cursor: pointer; width: 100%; text-align: center;">
						Ajukan Tempat
					</button>
				</div>
				
				<!-- Sidebar Information -->
				<div class="card-info-panel">
					<div class="card-info-panel-container1">
						<p class="card-info-panel-text-heading1 text3">Informasi Pengajuan</p>
						<p class="card-info-panel-text-paragraph text-text2">
							Setiap pengajuan akan disetujui oleh sesama mahasiswa UNIKOM sebelum dipublikasikan.
						</p>
					</div>
					
					<div class="card-info-panel-container2">
						<p class="card-info-panel-text-heading2 text4">Alur Pengajuan</p>
						
						<div class="card-info-panel-container3">
							<div class="card-info-panel-container4">
								<button type="button" class="btn-margin card-info-panel-btn-margin hover-dark">1</button>
								
								<div class="container-b container4">
									<p class="container-text-paragraph2">Isi form ini</p>
									<p class="container-text-paragraph3">
										Lengkapi setiap kolom dengan informasi yang benar dan detail.
									</p>
								</div>
							</div>
							
							<div class="container-c container5">
								<button type="button" class="btn-margin container-btn-margin hover-dark">2</button>
								
								<div class="container-b container1">
									<p class="container-text-paragraph2">Tinjau Komunitas</p>
									<p class="container-text-paragraph3">
										Pengajuan akan ditinjau oleh moderasi dan mahasiswa komunitas.
									</p>
								</div>
							</div>
							
							<div class="container-c container6">
								<button type="button" class="btn-margin container-btn-margin hover-dark">3</button>
								
								<div class="container-b container1">
									<p class="container-text-paragraph2">Dipublikasikan</p>
									<p class="container-text-paragraph3">
										Setelah disetujui, tempat akan muncul di pencarian Spotly.Kom.
									</p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card-info-panel-container5">
						<p class="card-info-panel-text-heading3 text4">Tips Pengajuan</p>
						
						<div class="card-info-panel-list">
							<div class="list-item list-item1">
								<div class="list-item-circle"></div>
								<p class="list-item-text">
									Gunakan foto yang jelas dan terang agar mudah dikenali.
								</p>
							</div>
							
							<div class="list-item list-item-margin">
								<div class="list-item-circle"></div>
								<p class="list-item-text">
									Berikan deskripsi yang detail dan informatif tentang tempat.
								</p>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>