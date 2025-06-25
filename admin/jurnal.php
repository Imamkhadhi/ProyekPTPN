<?php
include "template/header.php";
include "koneksi.php";
$currentPage = basename($_SERVER['PHP_SELF']);

// --- Pengaturan Pagination ---
$records_per_page = 24;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$total_records_sql = "SELECT COUNT(*) FROM jurnal";
$total_records_query = mysqli_query($conn, $total_records_sql);
if (!$total_records_query) {
    die("Query Gagal (Total Records): " . mysqli_error($conn));
}
$total_records = mysqli_fetch_array($total_records_query)[0];
$total_pages = ceil($total_records / $records_per_page);
if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $records_per_page;

$sqlstatement = "SELECT
    id_jurnal,
    tanggal,
    jam,
    uap_masuk,
    uap_bekas,
    tekanan_oli,
    cooling_water,
    voltase,
    ampere,
    kw,
    frekuensi
FROM jurnal ORDER BY id_jurnal DESC
LIMIT $records_per_page OFFSET $offset";

$query = mysqli_query($conn, $sqlstatement);
if (!$query) {
    die("Query Gagal (Data Jurnal): " . mysqli_error($conn));
}
$dataJurnal = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Mesin Turbin Maintenance Predict</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f4f4; padding-top: 80px; }
        .jurnal-container {
            max-width: 1200px; margin: 20px auto; padding: 20px;
            background-color: #fff; border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow-x: auto;
        }
        .jurnal-container h4 { text-align: center; margin-bottom: 20px; }
        .table-bordered { font-size: 0.85em; white-space: nowrap; }
        .table-bordered th, .table-bordered td {
            vertical-align: middle; min-width: 60px; padding: 8px;
        }
        .pagination-print-controls {
            display: flex; flex-direction: column; align-items: flex-start;
            margin-top: 15px; gap: 10px;
        }
        .pagination-print-controls .pagination { margin-bottom: 0; }
    </style>
</head>
<body>

<div class="jurnal-container">
    <h4>Jurnal Mesin Turbin</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle">Tanggal</th>
                <th rowspan="2" class="text-center align-middle">Jam</th>
                <th colspan="2" class="text-center">Uap Masuk (P1)</th>
                <th colspan="2" class="text-center">Uap Bekas (P2)</th>
                <th colspan="3" class="text-center align-middle">Tekanan Oil<br>(kg/cm²)</th>
                <th colspan="3" class="text-center">Temperatur Bantalan (°C)</th>
                <th colspan="2" class="text-center">Temperatur Cooling Water (°C)</th>
                <th colspan="5" class="text-center">Penunjukan di Panel Turbin</th>
                <th colspan="1" class="text-center">BPV</th>
                <th rowspan="2" class="text-center align-middle">Keterangan</th>
                <th rowspan="2" class="text-center align-middle">Rencana Tindak Lanjut</th>
                <th rowspan="2" class="text-center align-middle">Paraf Asisten</th>
                <th rowspan="2" class="text-center align-middle">Aksi</th>
            </tr>
            <tr>
                <th class="text-center">Tekanan<br>(kg/cm²)</th>
                <th class="text-center">Temp<br>(°C)</th>
                <th class="text-center">Tekanan<br>(kg/cm²)</th>
                <th class="text-center">Temp<br>(°C)</th>
                <th class="text-center">Lub</th>
                <th class="text-center">Cent</th>
                <th class="text-center">Impc</th>
                <th class="text-center">I</th>
                <th class="text-center">II</th>
                <th class="text-center">III</th>
                <th class="text-center">Masuk</th>
                <th class="text-center">Keluar</th>
                <th class="text-center">Voltage<br>(V)</th>
                <th class="text-center">Ampere<br>(A)</th>
                <th class="text-center">KW</th>
                <th class="text-center">Cos</th>
                <th class="text-center">Frekuensi<br>(Hz)</th>
                <th class="text-center">Tekanan<br>(kg/cm²)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataJurnal)): ?>
                <?php foreach ($dataJurnal as $row): ?>
                    <tr>
                        <td class="text-center"><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td class="text-center"><?= htmlspecialchars(substr($row['jam'], 0, 5)) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['uap_masuk']) ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center"><?= htmlspecialchars($row['uap_bekas']) ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center"><?= htmlspecialchars($row['tekanan_oli']) ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center"><?= htmlspecialchars($row['cooling_water']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['voltase']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['ampere']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['kw']) ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center"><?= htmlspecialchars($row['frekuensi']) ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $row['id_jurnal'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $row['id_jurnal'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="24" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination-print-controls">
        <nav>
            <ul class="pagination justify-content-start">
                <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
        <a href="pdf.php"><button type="button" class="btn btn-success">Print</button></a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
