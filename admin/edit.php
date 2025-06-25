<?php
include "template/header.php";
include "koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $uap_masuk = $_POST['uap_masuk'];
    $uap_bekas = $_POST['uap_bekas'];
    $tekanan_oli = $_POST['tekanan_oil'];
    $cooling_water = $_POST['temp_cooling_keluar'];
    $voltase = $_POST['voltase'];
    $ampere = $_POST['ampere'];
    $frekuensi = $_POST['frekuensi'];
    $kw = $_POST['kw'];

    $update_sql = "UPDATE jurnal SET
        tanggal = ?, jam = ?, uap_masuk = ?, uap_bekas = ?, tekanan_oli = ?,
        cooling_water = ?, voltase = ?, ampere = ?, frekuensi = ?, kw = ?
        WHERE id_jurnal = ?";

    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param(
            "ssiiiiiiiii",
            $tanggal, $jam, $uap_masuk, $uap_bekas, $tekanan_oli,
            $cooling_water, $voltase, $ampere, $frekuensi, $kw, $id
        );

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='jurnal.php';</script>";
        } else {
            echo "Gagal update: " . $stmt->error;
        }
        $stmt->close();
    }
    exit;
}

// Ambil data lama
$sql = "SELECT * FROM jurnal WHERE id_jurnal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Jurnal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <div class="main-content-container">
        <h2 class="mb-4 text-center">Edit Data Jurnal</h2>
        <form method="POST">
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Tanggal</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" name="tanggal" value="<?= $data['tanggal'] ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Jam</label>
                <div class="col-sm-8">
                    <input type="time" class="form-control" name="jam" value="<?= $data['jam'] ?>" required>
                </div>
            </div>

            <fieldset class="mb-3">
                <legend>Uap Masuk (P1)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Tekanan (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="uap_masuk" value="<?= $data['uap_masuk'] ?>" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Uap Bekas (P2)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Tekanan (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="uap_bekas" value="<?= $data['uap_bekas'] ?>" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Tekanan Oil</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Lub (kg/cm²)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="tekanan_oil" value="<?= $data['tekanan_oli'] ?>" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Temperatur Cooling Water (°C)</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Keluar</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="temp_cooling_keluar" value="<?= $data['cooling_water'] ?>" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend>Penunjukan di Panel Turbin</legend>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Voltage (V)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="voltase" value="<?= $data['voltase'] ?>" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Ampere (A)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="ampere" value="<?= $data['ampere'] ?>" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">Frekuensi (Hz)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="frekuensi" value="<?= $data['frekuensi'] ?>" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">KW</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.1" class="form-control" name="kw" value="<?= $data['kw'] ?>" required>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
