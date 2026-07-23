<?php
$host     = 'bore.pub'; 
$port     = '7416';          
$db_name  = 'tungbespaklurah';
$username = 'tungbes_teddy';
$password = 'prabski';  

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>