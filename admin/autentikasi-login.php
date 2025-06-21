<?php
include('koneksi.php');

$username = $_POST['username'];
$password = $_POST['password'];

$sqlstatement = "SELECT * FROM user WHERE username = '$username'";
$query = mysqli_query($conn, $sqlstatement);
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        alert('Username tidak terdaftar!');
        window.location.href = 'login.php';
    </script>";
} else {
    if ($password == $data['password']) {
        session_start();
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        header("Location: pengukuran.php");
        exit;
    } else {
        echo "<script>
            alert('Password salah!');
            window.location.href = 'login.php';
        </script>";
    }
}
?>
