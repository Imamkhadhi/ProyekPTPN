<?php
include "koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "DELETE FROM jurnal WHERE id_jurnal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='jurnal.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='jurnal.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ID tidak valid.'); window.location.href='jurnal.php';</script>";
}
