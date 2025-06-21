<?php

include "koneksi.php";

// Cek apakah ID ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Ambil ID dari URL

    // Hapus data tanah
    $delete_sql = "DELETE FROM tanah WHERE id = '$id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Data tanah berhasil dihapus.";
        header("Location: tanah.php"); // Redirect ke halaman tanah setelah berhasil
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID tidak ditentukan.";
    exit;
}
?>