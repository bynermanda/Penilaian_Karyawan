<?php
session_start(); // Memulai session agar bisa dihapus
session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan session di server

// Arahkan kembali ke halaman login
header("Location: login.html");
exit();
?>