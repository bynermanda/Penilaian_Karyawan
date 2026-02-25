<?php
$host = "localhost";
$db_name = "pt indosafety sentosa"; // Ganti dengan nama database di PHPMyAdmin
$username = "root";
$password = ""; // Default XAMPP biasanya kosong

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi Gagal: " . $e->getMessage();
}
?>