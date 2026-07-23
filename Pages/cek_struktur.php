<?php
require_once 'koneksi.php';
foreach (['ulasan', 'kunjungan'] as $tabel) {
    echo "<h3>$tabel</h3><pre>";
    print_r($pdo->query("DESCRIBE $tabel")->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
}