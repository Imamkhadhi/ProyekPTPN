<?php
include('koneksi.php');
include "template/header.php";

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
    $sql = "SELECT * FROM tanah WHERE id = '$id'"; 
    $result = $conn->query($sql);

    // Cek apakah tanah ditemukan
    if ($result->num_rows > 0) {
        $tanah = $result->fetch_assoc();
    } else {
        echo "Tanah tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak ditentukan.";
    exit;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $luas = $_POST['luas'];
    $harga = $_POST['harga'];
    $alamat = $_POST['alamat'];
    $sertifikat = $_POST['sertifikat'];
    $deskripsi = $_POST['deskripsi'];

    $foto = $_FILES['foto'];
    if (!empty($foto['name'])) {
        $photoname = time() . '_' . $foto['name'];
        move_uploaded_file($foto['tmp_name'], '../Assesment2/images/' . $photoname);
    } else {
        $photoname = "";
    }

    // Update data tanah
    $update_sql = "UPDATE tanah SET judul = '$judul', luas = '$luas', harga = '$harga', alamat = '$alamat', sertifikat = '$sertifikat', deskripsi = '$deskripsi', foto = '$photoname' WHERE id = '$id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Data tanah berhasil diperbarui.";
        header("Location: tanah.php"); // Redirect ke halaman tanah setelah berhasil
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tanah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-left: 300px;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Tanah</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($tanah['judul']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="luas" class="form-label">Luas</label>
                <input type="text" class="form-control" id="luas" name="luas" value="<?php echo htmlspecialchars($tanah['luas']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($tanah['harga']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($tanah['alamat']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sertifikat" class="form-label">Sertifikat</label>
                <input type="text" class="form-control" id="sertifikat" name="sertifikat" value="<?php echo htmlspecialchars($tanah['sertifikat']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($tanah['deskripsi']); ?></textarea>
            </div>
            <div class="input-group">
                <label for="gambar">Upload Gambar</label>
                <!-- <input type="file" name="gambar" id="gambar" required> -->
            </div>
            <div class="input-group">
                <input class="form-control" type="file" id="foto" name="foto"><br>
                <?php
                if(!empty($tanah['foto'])){
                ?>
                <img src="../Assesment2/images/<?= $tanah["foto"] ?>" width="100"><br>
                <?php
                }
                ?>
            </div>
            <div>
                <button type="submit" name="submit" class="upload-button">UPLOAD</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>