<?php
require_once "./library/konfigurasi.php";
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['idUser'])) {
    header("Location: /thehotel/system/");
    exit();
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password yang dimasukkan dengan SHA-256
    $hashed_password = hash('sha256', $password);

    // Query untuk memeriksa username dan password
    $user = query("SELECT * FROM user WHERE username = '$username' AND password = '$hashed_password'");

    // Debugging: Periksa apakah pengguna ditemukan
    if ($user) {
        // Jika berhasil, simpan idUser di session
        $_SESSION['idUser'] = $user[0]['idUser'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Buat token CSRF
        header("Location: /thehotel/system/"); // Redirect ke thehotel/system/4
        exit();
    } else {
        $error = "Username atau password salah.";
    }
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Login</title>
    <link href="<?= BASE_URL_HTML ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= BASE_URL_HTML ?>/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                        <?php if (isset($error)) : ?>
                                            <div class="alert alert-danger"><?= $error ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" id="exampleInputEmail" placeholder="Enter Username..." required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Password" required autocomplete="off">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL_HTML ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL_HTML ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL_HTML ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= BASE_URL_HTML ?>/js/sb-admin-2.min.js"></script>

</body>

</html>
