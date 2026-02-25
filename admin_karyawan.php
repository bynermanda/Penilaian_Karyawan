<?php
session_start();
require_once 'config.php';

// Proteksi Admin
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') {
    header("location:index.php");
    exit;
}

// PROSES SIMPAN (Tambah/Edit)
if (isset($_POST['simpan'])) {
    try {
        $id = $_POST['id_karyawan'];
        $nama = $_POST['nama'];
        $bagian = $_POST['bagian'];
        $jabatan = $_POST['jabatan'];
        $grade = $_POST['grade'];
        $status = $_POST['status'];

        // 1. Cek apakah ID sudah ada di database
        $check = $conn->prepare("SELECT id_karyawan FROM karyawan WHERE id_karyawan = ?");
        $check->execute([$id]);
        
        if ($check->rowCount() > 0) {
            // 2. Jika ADA, maka UPDATE
            $sql = "UPDATE karyawan SET nama=?, bagian=?, jabatan=?, grade=?, status=? WHERE id_karyawan=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nama, $bagian, $jabatan, $grade, $status, $id]);
            $msg = "Data berhasil diperbarui!";
        } else {
            // 3. Jika TIDAK ADA, maka INSERT baru
            $sql = "INSERT INTO karyawan (id_karyawan, nama, bagian, jabatan, grade, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $nama, $bagian, $jabatan, $grade, $status]);
            $msg = "Karyawan baru berhasil ditambahkan!";
        }

        echo "<script>alert('$msg'); window.location.href='admin_karyawan.php';</script>";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// PROSES HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM karyawan WHERE id_karyawan = ?");
    $stmt->execute([$id]);
    header("Location: admin_karyawan.php?msg=deleted");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Data Karyawan</h2>
        <a href="login.html" class="btn btn-secondary">Logut</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">Tambah / Update Karyawan</div>
        <div class="card-body">
            <form method="POST" id="formKaryawan" class="row g-3">
    <div class="col-md-3">
        <label class="form-label fw-bold text-primary">ID Karyawan</label>
        <input type="text" name="id_karyawan" id="id_karyawan" class="form-control form-control-lg" required placeholder="Contoh: 2024001">
    </div>
    <div class="col-md-5">
        <label class="form-label fw-bold text-primary">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" class="form-control form-control-lg" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-primary">Bagian (Dept)</label>
        <select name="bagian" id="bagian" class="form-select form-select-lg" required>
            <option value="">-- Pilih Bagian --</option>
            <option value="HRD">HRD</option>
            <option value="PRODUKSI">PRODUKSI</option>
            <option value="MAINTENANCE">MAINTENANCE</option>
            <option value="QC">QC</option>
            <option value="QA">QA</option>
            <option value="PURCHASING">PURCHASING</option>
            <option value="DIES">DIES</option>
            <option value="PRESS">PRESS</option>
            <option value="EXIM">EXIM</option>
            <Option value="IT (INFORMATION TECHNOLOGY)">IT (INFORMATION TECHNOLOGY)</Option>
            <option value="MARKETING">MARKETING</option>
            <Option value="FIN/ACC">FIN/ACC</Option>
            <option value="R&D">R&D</option>
            <option value="GA">GA</option>
            <Option value="PCC/WH2">PCC/WH2</Option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-primary">Jabatan</label>
        <select name="jabatan" id="jabatan" class="form-select form-select-lg" required>
            <option value="">-- Pilih Jabatan --</option>
            <option value="Grade 1">Grade 1</option>
            <option value="Grade 2">Grade 2</option>
            <option value="Grade 3">Grade 3</option>
            <option value="Grade 4">Grade 4</option>
            <option value="Grade 5">Grade 5</option>
            <option value="Grade 6">Grade 6</option>
            <option value="Grade 7">Grade 7</option>
            <option value="Grade 8">Grade 8</option>
            <option value="Grade 9">Grade 9</option>
            <option value="Grade 10">Grade 10</option>    
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-primary">Grade</label>
        <select name="grade" id="grade" class="form-select form-select-lg" required>
            <option value="">-- Pilih Grade --</option>
            <option value="Advisor">Advisor</option>
            <option value="Asst. Kepala Line">Asst. Kepala Line</option>
            <option value="Kepala Line">Kepala Line</option>
            <option value="Asst. Kepala Regu">Asst. Kepala Regu</option>
            <option value="Kepala Regu">Kepala Regu</option>
            <option value="Asst. Kepala Seksie">Asst. Kepala Seksie</option>
            <option value="Kepala Seksie">Kepala Seksie</option>
            <option value="Asst. Senior Staff">Asst. Senior Staff</option>
            <option value="Senior Staff">Senior Staff</option>
            <option value="Asst. Yunior Staff">Asst. Yunior Staff</option>
            <option value="DANRU">DANRU</option>
            <option value="Staff">Staff</option>
            <option value="Operator">Operator</option>
            <option value="Teknisi">Teknisi</option>
            <option value="Teknisi 1">Teknisi 1</option>
            <option value="Teknisi 2">Teknisi 2</option>
            <option value="Teknisi 3">Teknisi 3</option>
            <option value="Teknisi 4">Teknisi 4</option>
            <option value="Yunior Staff">Yunior Staff</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-primary">Status Kerja</label>
        <select name="status" id="status" class="form-select form-select-lg">
            <option value="Aktif">Aktif</option>
            <option value="Resign">Resign</option>
        </select>
    </div>

    <div class="col-12 text-end mt-4">
        <button type="reset" class="btn btn-outline-secondary btn-lg px-4 me-2">Reset</button>
        <button type="submit" name="simpan" class="btn btn-success btn-lg px-5 shadow">Simpan Data Karyawan</button>
    </div>
</form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabelAdminKaryawan" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Bagian</th>
                    <th>Jabatan</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $q = $conn->query("SELECT * FROM karyawan ORDER BY id_karyawan DESC");
    while($row = $q->fetch()) { 
        echo "<tr>
            <td>{$row['id_karyawan']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['bagian']}</td>
            <td>{$row['jabatan']}</td>
            <td>{$row['grade']}</td>
            <td><span class='badge bg-" . ($row['status'] == 'Aktif' ? 'success' : 'danger') . "'>{$row['status']}</span></td>
            <td class='text-center'>
                <button class='btn btn-sm btn-warning' onclick='isiForm(\"{$row['id_karyawan']}\", \"{$row['nama']}\", \"{$row['bagian']}\", \"{$row['jabatan']}\", \"{$row['grade']}\", \"{$row['status']}\")'>Edit</button>
                <button class='btn btn-sm btn-danger' onclick='konfirmasiHapus(\"{$row['id_karyawan']}\")'>Hapus</button>
            </td>
        </tr>";
    }
    ?>
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
    // Inisialisasi DataTables dengan limit 50 baris
    $('#tabelAdminKaryawan').DataTable({
        "pageLength": 50,
        "lengthMenu": [10, 25, 50, 100],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
        }
    });
});

// Fungsi Edit: Masukkan data tabel ke form
function isiForm(id, nama, bagian, jabatan, grade, status) {
    document.getElementById('id_karyawan').value = id;
    document.getElementById('nama').value = nama;
    document.getElementById('bagian').value = bagian;
    document.getElementById('jabatan').value = jabatan; 
    document.getElementById('grade').value = grade;     
    document.getElementById('status').value = status;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

// Fungsi Hapus dengan Konfirmasi Pop-up
function konfirmasiHapus(id) {
    if (confirm("Apakah Anda yakin ingin MENGHAPUS karyawan dengan ID: " + id + "? Data yang dihapus tidak bisa dikembalikan!")) {
        window.location.href = "admin_karyawan.php?hapus=" + id;
    }
}
</script>
</body>
</html>