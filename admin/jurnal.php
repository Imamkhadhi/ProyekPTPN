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
    die("Query Gagal (Total Records): " . mysqli_error($conn) . " SQL: " . $total_records_sql);
}

$total_records = mysqli_fetch_array($total_records_query)[0];

$total_pages = ceil($total_records / $records_per_page);

if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}
if ($current_page < 1) {
    $current_page = 1;
}
$offset = ($current_page - 1) * $records_per_page;

$sqlstatement = "SELECT
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
    die("Query Gagal (Data Jurnal): " . mysqli_error($conn) . " SQL: " . $sqlstatement);
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
        body {
            background-color: #f4f4f4;
            padding-top: 80px;
        }

        .custom-navbar {
            background: linear-gradient(to bottom, rgb(249, 249, 249), rgb(248, 251, 248));
            padding: 5px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }
        .navbar-brand img {
            height: 50px;
            width: 70px;
        }
        .logout-btn {
            color: black;
            font-size: 30px;
            text-decoration: none;
            margin-right: 0;
        }
        .logout-btn:hover {
            color: orange;
        }
        .navbar-nav .nav-link {
            color: black;
            font-weight: bold;
            margin-right: 20px;
        }
        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            border-bottom: 2px solid orange;
        }
        .custom-navbar .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .navbar-collapse {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-nav {
            flex-grow: 1;
            justify-content: center;
            display: flex;
        }
        @media (max-width: 768px) {
            .custom-navbar .container-fluid { padding-left: 1rem; padding-right: 1rem; }
            .navbar-brand { margin-right: 0; padding-left: 0; }
            .navbar-brand img { height: 40px; width: auto; }
            .navbar-toggler { margin-left: auto; }
            .navbar-collapse { display: block; justify-content: start; align-items: start; }
            .navbar-nav {
                background-color: rgb(253, 253, 253); border-radius: 5px; padding: 5px; text-align: left;
                margin-left: 0 !important; margin-top: 10px; width: 100%; display: block;
                justify-content: start; flex-grow: unset;
            }
            .navbar-nav .nav-item { text-align: left; width: 100%; }
            .navbar-nav .nav-link { display: block; width: 100%; padding-left: 10px; margin-right: 0; }
            .collapse.navbar-collapse .ms-auto {
                display: block; width: 100%; text-align: left; padding: 10px;
                margin-top: 10px; margin-left: 0 !important;
            }
            .collapse.navbar-collapse .ms-auto .logout-btn {
                font-size: 20px; text-align: left; display: block; width: 100%;
            }
        }

        .jurnal-container {
            max-width: 1200px;
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            box-sizing: border-box;
            overflow-x: auto;
        }

        .jurnal-container h4 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .filter-search-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        .filter-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-section input {
            max-width: 150px;
        }

        .search-input {
            max-width: 300px;
            flex-grow: 1;
        }

        .table-bordered {
            font-size: 0.85em;
            white-space: nowrap;
        }

        .table-bordered th, .table-bordered td {
            vertical-align: middle;
            min-width: 60px;
            padding: 8px;
        }

        .table-bordered thead th {
            background-color: #e9ecef;
            border-bottom: 2px solid #dee2e6;
        }

        /* Responsif Tabel */
        @media (max-width: 768px) {
            .jurnal-container {
                padding: 15px;
                margin: 15px auto;
            }
            .jurnal-container h4 {
                font-size: 1.2em;
            }
            .filter-search-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .filter-section, .search-input {
                width: 100%;
                max-width: none;
            }
            .filter-section input {
                width: 100%;
                max-width: none;
            }
        }

        /* Gaya untuk pagination dan tombol print di bawah tabel */
        .pagination-print-controls {
            display: flex;
            flex-direction: column; /* Atur menjadi kolom untuk menumpuk elemen */
            align-items: flex-start; /* Sejajarkan semua ke kiri */
            margin-top: 15px; /* Jarak dari tabel di atasnya */
            gap: 10px; /* Jarak antara pagination dan tombol print */
        }
        .pagination-print-controls .pagination {
            margin-bottom: 0; /* Hapus margin bawah default dari pagination */
        }
    </style>
</head>
<body>

    <div class="jurnal-container">
        <h4>Jurnal Mesin Turbin Maintenance Predict</h4>
        <div class="filter-search-row">
            <div class="filter-section">
                <label for="start-date" class="form-label mb-0">Tanggal:</label>
                <input type="date" id="start-date" class="form-control">
                <input type="date" id="end-date" class="form-control">
                <button class="btn btn-success">Filter</button>
            </div>
            <input type="text" class="form-control search-input" placeholder="Search">
        </div>

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
                </tr>
                <tr>
                    <th class="text-center">Tekanan<br>(kg/cm²)</th>
                    <th class="text-center">Temp<br>(°C)</th>
                    <th class="text-center">Tekanan<br>(kg/cm²)</th>
                    <th class="text-center">Temp<br>(°C)</th>
                    <th class="text-center">Lub<br></th>
                    <th class="text-center">Cent<br></th>
                    <th class="text-center">Impc<br></th>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">III</th>
                    <th class="text-center">Masuk</th>
                    <th class="text-center">Keluar</th>
                    <th class="text-center">Voltage<br>(V)</th>
                    <th class="text-center">Ampere<br>(A)</th>
                    <th class="text-center">KW<br></th>
                    <th class="text-center">Cos<br></th>
                    <th class="text-center">Frekuensi<br>(Hz)</th>
                    <th class="text-center">Tekanan<br>(kg/cm²)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataJurnal)): ?>
                    <?php foreach ($dataJurnal as $row): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars(date('Y-m-d', strtotime($row['tanggal']))) ?></td>
                            <td class="text-center"><?= htmlspecialchars(date('H:i', strtotime($row['jam']))) ?></td>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="24" class="text-center">Tidak ada data jurnal yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-print-controls">
            <nav>
                <ul class="pagination justify-content-start"> <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
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
            <a href="pdf.php"><button type="button" class="btn btn-success print-button">Print</button></a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>