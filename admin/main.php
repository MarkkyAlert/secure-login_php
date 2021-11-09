<?php

include('../main.php');
check_login($db, "../login.php");
is_not_admin($_SESSION['role'], "../404page.html");

function admin_header($title)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>$title</title>
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="../css/sb-admin-2.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap');

            body {
                font-family: 'Kanit', sans-serif;
            }
        </style>
    </head>

    <body id="page-top">

        <div id="wrapper">

            <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                    <div class="sidebar-brand-text mx-3">DevMark</div>
                </a>

                <hr class="sidebar-divider my-0">

                <li class="nav-item active">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-user-cog"></i>
                        <span>บัญชี</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>ออกจากระบบ</span></a>
                </li>
                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
            </ul>

            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Search -->

                        <!-- Topbar Navbar -->
                        <h4 class="">ADMIN PANEL</h4>

                    </nav>
                    <!-- End of Topbar -->
EOT;
}

function admin_footer() {
    echo <<<EOT
    </div>
    </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
    </body>
    </html>
EOT;
}
