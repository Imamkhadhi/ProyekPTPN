<?php
include "template/header.php";
include "koneksi.php";
$currentPage = basename($_SERVER['PHP_SELF']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $uap_masuk_tek = $_POST['uap_masuk'];
    $uap_bekas_tek = $_POST['uap_bekas'];
    $tekanan_oil_lub = $_POST['tekanan_oil'];
    $temp_cooling_keluar = $_POST['temp_cooling_keluar'];
    $panel_voltage = $_POST['voltase'];
    $panel_ampere = $_POST['ampere'];
    $panel_frekuensi = $_POST['frekuensi'];
    $kw = $_POST['kw'];

    $insert_sql = "INSERT INTO jurnal (
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
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insert_sql)) {
        $stmt->bind_param(
            "ssiiiiiiii",
            $tanggal,
            $jam,
            $uap_masuk_tek,
            $uap_bekas_tek,
            $tekanan_oil_lub,
            $temp_cooling_keluar,
            $panel_voltage,
            $panel_ampere,
            $kw,
            $panel_frekuensi
        );

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='jurnal.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Tabel Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            padding-top: 80px;
        }

        .main-content-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        fieldset {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        legend {
            font-size: 1.1em;
            font-weight: bold;
            color: #555;
        }

        @media (max-width: 768px) {
            .main-content-container {
                padding: 15px;
            }

            .row.mb-3, .row.mb-2 {
                display: block;
            }

            .col-sm-4, .col-sm-8 {
                width: 100%;
            }

            legend {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

    <div class="main-content-container">
        <h2 class="mb-4 text-center">Form Input Tabel Monitoring</h2>
        <form method="POST" action="pengisian.php">

            <!-- Input Tanggal -->
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Tanggal</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" name="tanggal" required>
                </div>
            </div>

            <!-- Input Jam -->
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Jam</label>
                <div class="col-sm-8">
                    <input type="time" class="form-control" name="jam" required>
                </div>
            </div>

            <fieldset class="mb-3">
                <legend>Uap Masuk (P1)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Tekanan (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="uap_masuk" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Uap Bekas (P2)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Tekanan (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="uap_bekas" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Tekanan Oil</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Lub (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="tekanan_oil" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Temperatur Cooling Water (°C)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Keluar</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="temp_cooling_keluar" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Penunjukan di Panel Turbin</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Voltage (V)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="voltase" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Ampere (A)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="ampere" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Frekuensi (Hz)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="frekuensi" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">KW</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="kw" required>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
