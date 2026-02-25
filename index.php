<?php
session_start();
// Proteksi: Jika belum login, tendang ke login.html
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.html");
    exit;
}

// Ambil data dari session untuk ditampilkan di HTML
$nama_user = $_SESSION['username'];
$level_user = $_SESSION['level'];
$bagian_user = $_SESSION['bagian'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penilaian Karyawan - PT Indosafety Sentosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar Start-->
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
            <span class="fw-bold">PT Indosafety Sentosa</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form_penilaian.html">Form Penilaian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rekap_bulanan.html">Penilaian Bulanan</a>
                </li>
            </ul>
        </div>

        <div class="d-flex">
            <a href="logout.php" class="btn btn-outline-danger btn-sm px-4 shadow-sm">Logout</a>
        </div>
    </div>
</nav>
<!-- Navbar end-->

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 id="judulBagian">Daftar Karyawan</h2>
            <p class="text-muted">Akun Login: <span id="id_user" class="fw-bold"><?php echo $nama_user; ?></span></p>
            <p class="text-muted">Level Akun: <span id="bagian_user" class="fw-bold"><?php echo $level_user; ?></span></p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Data Staff Bagian <span id="labelBagian">...</span></h4>
        </div>
        <div class="card-body">
            <table id="tabelKaryawan" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr class="table-secondary">
                        <th>ID Karyawan</th>
                        <th>Nama</th>
                        <th>Bagian</th>
                        <th>Jabatan</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody id="isiTabel">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Panggil API get_karyawan_per_bagian.php
    fetch('get_karyawan_perbagian.php')
        .then(response => response.json())
        .then(res => {
            

            console.log("Data diterima:", res);

            if(res.status === 'success') {
                // Update informasi User & Bagian di UI
                $('#namaUser').text(res.info.user);
                $('#labelBagian').text(res.info.bagian_penilai);
                
                let rows = '';
                res.data.forEach((k) => {
                    rows += `
                        <tr>
                            <td>${k.id_karyawan || '-'}</td>
                            <td>${k.nama || '-'}</td>
                            <td>${k.bagian || '-'}</td>
                            <td>${k.jabatan || '-'}</td>
                            <td>${k.grade || '-'}</td>
                        </tr>
                    `;
                });
                
                $('#isiTabel').html(rows);
                
                // Jalankan DataTable
                $('#tabelKaryawan').DataTable({
                    "pageLength": 10,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                    }
                });
            } else {
                // Jika error (Unauthorized), lempar ke halaman login
                alert(res.message);
                window.location.href = 'login.html';
            }
            
        })
        .catch(err => {
            console.error("Error Fetching:", err);
            $('#isiTabel').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data API. Pastikan Anda sudah login.</td></tr>');
        });
});
</script>
</body>
</html>