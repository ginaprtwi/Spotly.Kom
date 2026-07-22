<?php
session_start();
require_once __DIR__ . '\koneksi.php';

// Ensure user is logged in
if (!isset($_SESSION['nim'])) {
    header("Location: masuk.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim        = $_SESSION['nim'];
    $id_tempat  = intval($_POST['id_tempat'] ?? 0);
    $jenis_vote = $_POST['jenis_vote'] ?? ''; // 'UPVOTE' or 'DOWNVOTE'
    $redirect   = $_POST['redirect'] ?? 'pencarian.php';

    if ($id_tempat > 0 && in_array($jenis_vote, ['UPVOTE', 'DOWNVOTE'])) {
        try {
            // INSERT or UPDATE vote on duplicate primary key (nim, id_tempat)
            $sql = "INSERT INTO vote (jenis_vote, tgl_vote, nim, id_tempat)
                    VALUES (:jenis_vote, CURDATE(), :nim, :id_tempat)
                    ON DUPLICATE KEY UPDATE 
                        jenis_vote = VALUES(jenis_vote),
                        tgl_vote = CURDATE()";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':jenis_vote' => $jenis_vote,
                ':nim'        => $nim,
                ':id_tempat'  => $id_tempat
            ]);

            // OPTIONAL: Auto-approve place if UPVOTES >= threshold (e.g., 3 upvotes)
            $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM vote WHERE id_tempat = ? AND jenis_vote = 'UPVOTE'");
            $stmtCount->execute([$id_tempat]);
            $upvoteCount = $stmtCount->fetchColumn();

            if ($upvoteCount >= 3) {
                $stmtApprove = $pdo->prepare("UPDATE tempat_belajar SET status_tempat = 'Disetujui' WHERE id_tempat = ?");
                $stmtApprove->execute([$id_tempat]);
            }

        } catch (PDOException $e) {
            // Handle error or log
        }
    }

    header("Location: " . $redirect);
    exit;
}
?>