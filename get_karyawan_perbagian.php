<?php
session_start();
require_once 'config.php';

$bagian = $_SESSION['bagian'];
$bulan_ini = date("F Y"); // Harus sama formatnya dengan saat simpan

try {
    // Query untuk mengambil karyawan berdasarkan bagian yang sedang login, sekaligus cek apakah sudah dinilai bulan ini
$sql = "SELECT 
                k.id_karyawan, 
                k.nama, 
                k.bagian, 
                k.grade, 
                k.jabatan, 
                p.id_penilaian 
            FROM karyawan k 
            LEFT JOIN penilaian p ON k.id_karyawan = p.id_karyawan AND p.bulan_tahun = ?
            WHERE k.bagian = ? AND k.status = 'Aktif'";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([$bulan_ini, $bagian]);
    $karyawan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $karyawan,
        "info" => ["bagian_penilai" => $bagian, "bulan" => $bulan_ini]
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>