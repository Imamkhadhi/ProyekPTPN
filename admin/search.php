<?php
include "template/header.php";
include "koneksi.php";

// Ambil keyword dari form pencarian
$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';

// Query pencarian
$sqlstatement = "SELECT * FROM tanah WHERE judul LIKE '%$keyword%' OR alamat LIKE '%$keyword%'";
$query = mysqli_query($conn, $sqlstatement);
$dataTanah = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<div class="search">
    <form method="GET" action="search.php">
        <input type="text" name="keyword" placeholder="Cari berdasarkan judul atau alamat..." class="form-control" style="width: 300px; display: inline-block; margin-left: 400px;" value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<h3 style="margin-left: 400px;">Hasil Pencarian untuk "<?php echo htmlspecialchars($keyword); ?>"</h3>

<div class="table">
    <table class="table table-striped table-bordered table-hover table-rounded mt-3">
        <thead class="table-secondary">
            <tr>
                <th>Judul</th>
                <th>Luas</th>
                <th>Harga</th>
                <th>Alamat</th>
                <th>Sertifikat</th>
                <th>Deskripsi</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataTanah)): ?>
                <?php foreach ($dataTanah as $tanah): ?>
                    <tr>
                        <td><?php echo $tanah['judul']; ?></td>
                        <td><?php echo $tanah['luas']; ?></td>
                        <td><?php echo $tanah['harga']; ?></td>
                        <td><?php echo $tanah['alamat']; ?></td>
                        <td><?php echo $tanah['sertifikat']; ?></td>
                        <td><?php echo $tanah['deskripsi']; ?></td>
                        <td><img src="../images/<?php echo $tanah['foto']; ?>" alt="Tanah Image" style="width: 50px; height: 40px;"></td>
                        <td align="center">
                            <a href="edittanah.php?id=<?php echo $tanah['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="deleteTanah.php?id=<?php echo $tanah['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan menghapus?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" align="center">Tidak ada data ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>