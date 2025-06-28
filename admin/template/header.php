<?php 
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login
    header("Location: ../login.php");
    exit;
}

include "koneksi.php"; 
define('HOST', "http://localhost/assesment2/index.html");
$currentPage = basename($_SERVER['PHP_SELF']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Monitoring</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <script src="https://cdn.jsdelivr/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f4f4f4;
    }

    .custom-navbar {
      background: linear-gradient(to bottom, rgb(249, 249, 249), rgb(248, 251, 248));
      padding: 5px 0;
    }

    .navbar-brand img {
      height: 50px;
      width: 100px;
    }

    /* Penyesuaian untuk .navbar-brand di desktop */
    .navbar-brand {
      padding-left: 0 !important; /* Hapus padding default Bootstrap jika tidak diinginkan */
      /* margin-right: 2rem;  Tidak perlu jika justify-content: space-between digunakan */
    }

    /* Penyesuaian untuk .logout-btn di desktop */
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
      margin-right: 20px; /* Jarak antar item menu */
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:hover { 
      border-bottom: 2px solid orange;
    }

    /* Untuk mengatur jarak logo ke kiri halaman dan logout ke kanan halaman */
    .custom-navbar .container-fluid {
      padding-left: 1.5rem; /* Sesuaikan nilai ini sesuai keinginan Anda (misal 24px) */
      padding-right: 1.5rem; /* Sesuaikan nilai ini agar sama */
    }

    /* **KUNCI UNTUK MEMUSATKAN MENU DI DESKTOP** */
    /* Pastikan navbar-collapse adalah flex container dan elemen didalamnya terdistribusi */
    .navbar-collapse {
        display: flex;
        justify-content: space-between; /* Distribusikan ruang di antara elemen */
        align-items: center;
    }

    /* Untuk membuat menu navigasi berada di tengah */
    .navbar-nav {
        /* Hapus mx-auto dari HTML jika menggunakan flex-grow dan justify-content di sini */
        flex-grow: 1; /* Ambil semua ruang yang tersedia */
        justify-content: center; /* Pusatkan item-item navigasi di dalam .navbar-nav */
        display: flex; /* Jadikan .navbar-nav juga flex container */
    }


    /* Media query untuk tampilan mobile */
    @media (max-width: 768px) {
      .custom-navbar .container-fluid {
          padding-left: 1rem; /* Sedikit lebih kecil untuk mobile */
          padding-right: 1rem;
      }

      .navbar-brand {
          margin-right: 0; /* Hapus margin kanan di mobile */
          padding-left: 0; /* Pastikan tidak ada padding kiri yang aneh */
      }
      
      .navbar-brand img {
          height: 40px; 
          width: auto;
      }

      .navbar-toggler {
          margin-left: auto; /* Dorong toggler ke kanan di mobile */
      }

      /* Override flexbox untuk navbar-collapse di mobile agar tidak terdistribusi */
      .navbar-collapse {
          display: block; /* Kembali ke block untuk tampilan collapse */
          justify-content: start; /* Tidak perlu distribusi ruang saat collapse */
          align-items: start;
      }

      .navbar-nav {
        background-color: rgb(253, 253, 253); 
        border-radius: 5px;
        padding: 5px;
        text-align: left;
        margin-left: 0 !important; 
        margin-top: 10px; 
        width: 100%;
        /* Kembali ke display block untuk mobile menu */
        display: block; 
        justify-content: start; /* Tidak perlu pemusatan di mobile */
        flex-grow: unset; /* Hapus flex-grow di mobile */
      }

      .navbar-nav .nav-item {
        text-align: left;
        width: 100%;
      }

      .navbar-nav .nav-link {
        display: block;
        width: 100%;
        padding-left: 10px; 
        margin-right: 0; 
      }

      .collapse.navbar-collapse .ms-auto {
          display: block; 
          width: 100%; 
          text-align: left; 
          padding: 10px; 
          margin-top: 10px; 
          margin-left: 0 !important; 
      }
       .collapse.navbar-collapse .ms-auto .logout-btn {
           font-size: 20px; 
           text-align: left;
           display: block;
           width: 100%;
       }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 9999;">
  <div class="container-fluid"> 
    <a class="navbar-brand" href="#">
      <img src="../images/logo_perkebunan_nusantara.png" alt="Logo PTPN" />
    </a>

    <button class="navbar-toggler text-white ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= $currentPage == 'pengukuran.php' ? 'active' : '' ?>" href="pengukuran.php">Monitoring</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage == 'pengisian.php' ? 'active' : '' ?>" href="pengisian.php">Pengisian</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage == 'jurnal.php' ? 'active' : '' ?>" href="jurnal.php">Jurnal</a>
        </li>
      </div>

      <div class="ms-auto d-none d-lg-block">
        <a class="logout-btn" href="logout.php" title="Logout">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      </div>

      <div class="ms-auto d-lg-none">
        <a class="logout-btn" href="logout.php" title="Logout">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </div>
</nav>


</body>
</html>