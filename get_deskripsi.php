<?php
session_start();
require_once 'config.php';

$bagian = $_SESSION['bagian'];

try {
    $stmt = $conn->prepare("SELECT * FROM deskripsi_kriteria WHERE bagian = ?");
    $stmt->execute([$bagian]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);


    // Jika belum ada deskripsi di database, kirim pesan default
    if(!$data) {
        echo json_encode(["status" => "error", "message" => "Deskripsi belum diatur"]);
        // Saat menampilkan data dari database
    echo "<td>" . nl2br($row['desc1']) . "</td>";
    } else {
        echo json_encode(["status" => "success", "data" => $data]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>