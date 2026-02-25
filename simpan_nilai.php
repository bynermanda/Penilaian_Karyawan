<?php
session_start();
header("Content-Type: application/json");
require_once 'config.php';

// Tambahkan di simpan_nilai.php setelah session_start dan config.php

$id_karyawan = $_POST['id_karyawan'];
$bulan_ini = date("F Y");

// CEK APAKAH SUDAH PERNAH DINILAI
$cek = $conn->prepare("SELECT id_penilaian FROM penilaian WHERE id_karyawan = ? AND bulan_tahun = ?");
$cek->execute([$id_karyawan, $bulan_ini]);

if ($cek->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "Karyawan ini sudah dinilai untuk periode $bulan_ini!"]);
    exit;
}

// ... Jika belum ada, baru lanjutkan proses INSERT ke bawah ...

// 1. Pastikan user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    echo json_encode(["status" => "error", "message" => "Sesi habis, silakan login kembali"]);
    exit;
}

// 2. Tangkap data dari POST
$id_karyawan = $_POST['id_karyawan'];
$id_user     = $_SESSION['id_user']; // Menggunakan id_user INT yang baru dibuat
$bulan_ini   = date("F Y"); // Contoh: January 2026

// Nilai mentah (A=100, B=80, dst)
$s1 = (int)$_POST['val1']; // Kehadiran
$s2 = (int)$_POST['val2']; // Terlambat
$s3 = (int)$_POST['val3']; // Kualitas
$s4 = (int)$_POST['val4']; // Efisiensi
$s5 = (int)$_POST['val5']; // Sikap
$s6 = (int)$_POST['val6']; // Keselamatan

// 3. Hitung Total Akhir berdasarkan Bobot Persentase Anda
// (5% + 5% + 30% + 20% + 20% + 20% = 100%)
$total_skor = ($s1 * 0.05) + ($s2 * 0.05) + ($s3 * 0.30) + ($s4 * 0.20) + ($s5 * 0.20) + ($s6 * 0.20);

try {
    // 4. Masukkan ke Database
    $query = "INSERT INTO penilaian (
                id_karyawan, id_user, bulan_tahun, 
                skor_kehadiran, skor_terlambat, skor_kualitas, 
                skor_efisiensi, skor_sikap, skor_keselamatan, 
                total_akhir
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([
        $id_karyawan, $id_user, $bulan_ini, 
        $s1, $s2, $s3, $s4, $s5, $s6, $total_skor
    ]);

    echo json_encode([
        "status" => "success", 
        "message" => "Data penilaian untuk staff $id_karyawan telah disimpan."
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
}
?>