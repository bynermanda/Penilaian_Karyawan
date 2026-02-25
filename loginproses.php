<?php
session_start();
require_once 'config.php';

$user_input = $_POST['username'];
$pass_input = $_POST['password'];

$query = "SELECT * FROM users WHERE username = :username LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $user_input);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $pass_input === $user['password']) {
    // Di dalam proses login yang berhasil:
// ... kode koneksi ...
if ($user && $pass_input === $user['password']) {
    session_start();
    $_SESSION['status'] = "login";
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['bagian'] = $user['bagian'];
    $_SESSION['level'] = $user['level']; // Buat menyimpan level user (Admin, Penilai, Karyawan)

    if ($_SESSION['level'] === 'Admin') {
    // Jika Admin, langsung ke halaman manajemen
    echo "<script>alert('Selamat Datang Admin!'); window.location.href='admin_karyawan.php';</script>";
} else {
    // Jika User biasa, ke dashboard utama
    echo "<script>alert('Login Berhasil!'); window.location.href='index.php';</script>";
}

    echo "<script>alert('LOGIN BERHASIL" . $_SESSION['bagian'] . "'); 
    window.location.href='index.php';
    </script>";
}
// ...
} else 
    echo "<script>
            alert('Username atau Password Salah!');
            window.location.href='login.html';
          </script>";
?>