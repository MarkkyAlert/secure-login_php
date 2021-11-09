<?php
include('../main.php');

$roles = array('Admin', 'Member');

if (isset($_GET['user_id'])) {
    $select_stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $select_stmt->bindParam(':user_id', $_GET['user_id']);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($_POST['delete'])) {
        $delete_stmt = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
        $delete_stmt->bindParam(':user_id', $_POST['user_id']);
        $delete_stmt->execute();
        header('location: index.php');
        exit;
    }
    else if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $activation_code = $_POST['activation_code'];
        $remember = $_POST['remember'];
        $role = $_POST['role'];
        if (empty($_POST['password'])) {
            $password = $row['password'];
        }
        else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $update_stmt = $db->prepare("UPDATE users SET email = :email, password = :password, activation_code = :activation_code, remember = :remember, role = :role WHERE user_id = :user_id");
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':password', $password);
        $update_stmt->bindParam(':activation_code', $activation_code);
        $update_stmt->bindParam(':remember', $remember);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':user_id', $_GET['user_id']);
        $update_stmt->execute();
        header('location: index.php');
        exit;
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

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
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

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">

                <div class="sidebar-brand-text mx-3">DevMark</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>บัญชี</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>ตั้งค่า</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>ออกจากระบบ</span></a>
            </li>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1>แก้ไขบัญชี</h1>
                    <hr>


                    <div class="card shadow mb-4">

                        <div class="card-body">
                            <form class="edit-form" action="" method="post">

                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" id="exampleInputEmail1">

                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Activation code</label>
                                    <input type="text" class="form-control" name="activation_code" value="<?php echo $row['activation_code']; ?>" id="exampleInputPassword1">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Remember me code</label>
                                    <input type="text" class="form-control" name="remember" value="<?php echo $row['remember']; ?>" id="exampleInputPassword1">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">บทบาท</label>
                                    <select class="form-control" name="role" aria-label="Default select example">
                                        <?php foreach ($roles as $role) : ?>
                                            <option value="<?php echo $role; ?>" <?php if ($role == $row['role']) {echo "selected";} ?>><?php echo $role; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">

                                <button type="submit" name="submit" class="btn btn-primary w-100 mt-3">บันทึก</button>
                                <button type="button" class="btn btn-danger w-100 mt-3" data-toggle="modal" data-target="#exampleModal">ลบ</button>



                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">แจ้งเตือน</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                คุณต้องการลบรายการนี้ใช่หรือไม่
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" name="delete" class="btn btn-primary">ตกลง</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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