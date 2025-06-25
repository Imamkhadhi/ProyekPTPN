<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - PTPN</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-image: url(../images/login-bg4.png);
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      min-height: 100vh;
    }

    .nav-left img {
      width: 100px;
      height: 40px;
    }

    .nav-left a {
      text-decoration: none;
      margin-top: 15px;
    }

    .login-section {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      padding: 40px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 900px; /* Lebar ditingkatkan */
      margin: auto;
    }

    .login-form label {
      margin-top: 10px;
    }

    .login-form input {
      border-radius: 5px;
      padding: 8px;
    }

    .login-form button {
      background-color: #1f4d1f;
      color: white;
      width: 100%;
      padding: 10px;
      border: none;
      margin-top: 20px;
      border-radius: 5px;
    }

    .login-form button:hover {
      background-color: #387c38;
    }

    @media (max-width: 768px) {
      .login-card {
        padding: 20px;
        max-width: 100%;
      }

      .text-end,
      .text-center {
        text-align: center !important;
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header class="p-3 d-flex align-items-center">
    <div class="nav-left d-flex align-items-center">
      <img src="../images/logo_perkebunan_nusantara.jpg" alt="Logo">
      <a href="index.html">
        <h1 class="ms-2" style="color: black;">PT<span style="color: green;">PN</span></h1>
      </a>
    </div>
  </header>

  <!-- Main Content -->
  <main>
    <section class="login-section container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="login-card">
            <div class="mb-3">
              <a href="index.html"></a>
            </div>
            <h2 class="mb-3 text-center">Login To your Account</h2>
            <form class="login-form" action="autentikasi-login.php" method="post">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" class="form-control" required>

              <label for="password">Password</label>
              <input type="password" name="password" id="password" class="form-control" required>

              <button type="submit" name="btn-submit">Login</button>

              <div class="text-end mt-2">
                <a href="#">Forgot Password?</a>
              </div>

              <!-- <div class="text-center mt-3">
                <span>Belum punya akun?</span> <a href="register.html">Register disini</a>
              </div> -->
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

</body>

</html>
