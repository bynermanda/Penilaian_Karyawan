<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require_once 'config.php';

try {
    // Sesuaikan nama tabel jika masih 'Karyawan PT.X' gunakan backtick ``
    $query = "SELECT `id_karyawan`, `nama`, `bagian`, `jabatan`, `grade` FROM karyawan";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $karyawan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "total" => count($karyawan),
        "data" => $karyawan
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>