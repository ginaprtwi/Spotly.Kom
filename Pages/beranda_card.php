<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/koneksi.php';

$search = $_GET['q'] ?? '';

$sql = "SELECT tb.id_tempat, tb.nama_tempat, tb.foto, tb.fasilitas,
               COUNT(DISTINCT k.id_kunjungan) AS jumlah_kunjungan,
               ROUND(AVG(u.rating), 1) AS rata_rating
        FROM tempat_belajar tb
        LEFT JOIN kunjungan k ON tb.id_tempat = k.id_tempat
        LEFT JOIN ulasan u ON k.id_kunjungan = u.id_kunjungan
        WHERE tb.status_tempat = 'Disetujui'";

$params = [];
if (!empty($search)) {
    $sql .= " AND tb.nama_tempat LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " GROUP BY tb.id_tempat ORDER BY jumlah_kunjungan DESC, rata_rating DESC LIMIT 6";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$topRekomendasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($topRekomendasi) > 0): ?>
	<?php foreach ($topRekomendasi as $tempat): ?>
		<a href="detail.php?id=<?= $tempat['id_tempat'] ?>" class="card card1" style="text-decoration: none; color: inherit;">
			<img src="../assets/tempat/<?= htmlspecialchars($tempat['foto']) ?>" class="card-img" />
			<div class="card-container1">
				<p class="text-dark"><?= htmlspecialchars($tempat['nama_tempat']) ?></p>
				<div class="card-container2">
					<?php
					$fasilitas = explode(',', $tempat['fasilitas']);
					foreach ($fasilitas as $f):
					?>
						<span class="btn-text hover-dark"><?= htmlspecialchars(trim($f)) ?></span>
					<?php endforeach; ?>
				</div>
				<div class="card-container3">
					<object data="../assets/assets1/col/card-container/card-icon2.svg" class="card-icon" type="image/svg+xml"></object>
					<p class="card-text"><?= $tempat['rata_rating'] ?? '0.0' ?></p>
				</div>
			</div>
		</a>
	<?php endforeach; ?>
<?php else: ?>
	<p>Tidak ada tempat yang cocok dengan pencarian kamu.</p>
<?php endif; ?>