<?php
error_reporting(E_ALL);

session_start();
require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$roomType = query("SELECT * FROM roomtypes");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>The Hotel</title>

    <!-- Custom fonts for this template-->
    <link href="<?= BASE_URL_HTML ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL_HTML ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />





</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/sidebar.php" ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once "{$constant('BASE_URL_PHP')}/system/navbar.php" ?>

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">ROOM DATA</h1>
                    </div>

                    <div class="row d-flex shadow p-2">
                        <form class="d-none d-sm-inline-block form-inline ml-md-3 my-2 my-md-0 mw-100 navbar-search border">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search Room Number" aria-label="Search" aria-describedby="basic-addon2" id="searchQuery"  autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="cariDaftarRoom()">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form id="formExcel" class="ml-2 flex row">
                        <div class="ml-2 ">
                            <select class="custom-select" id="roomStatus" name="roomStatus" onclick="cariDaftarRoom()">
                                <option value="">All</option>
                                <option value="Available">Available</option>
                                <option value="Maintenance" >Maintenance</option>
                                <option value="Booked">Booked</option>
                            </select>
                        </div>
                        <div class="ml-2 ">
                            <select class="custom-select" id="limit" name="limit" onclick="cariDaftarRoom()">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50" >50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                            <!-- <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success m-1 shadow-sm ml-2 p-1" onclick="exportExcel()">
                            <i class="fa-solid fa-file-excel"></i> Download Report
                            </button> -->
                        </form>
                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-auto" data-toggle="modal" data-target="#roomModal">
                            <i class="fas fa-plus fa-sm text-white"></i> Add Room
                        </button>
                    </div>

                    <div class="row" id="daftarRoom">
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- MODAL ADD ROOM -->
  
    <!-- MODAL ADD ROOM -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= BASE_URL_HTML ?>/login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= BASE_URL_HTML ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL_HTML ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= BASE_URL_HTML ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= BASE_URL_HTML ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= BASE_URL_HTML ?>/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= BASE_URL_HTML ?>/js/demo/chart-area-demo.js"></script>
    <script src="<?= BASE_URL_HTML ?>/js/demo/chart-pie-demo.js"></script>
    <script src="<?= BASE_URL_HTML ?>/system/room/room.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

</body>

</html>