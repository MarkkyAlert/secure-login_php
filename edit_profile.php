<?php
include('main.php');
check_login($db);

if (isset($_REQUEST['user_id'])) {
    $user_id = $_REQUEST['user_id'];
    $select_stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $select_stmt->bindParam(':user_id', $user_id);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
}
if (isset($_POST['submit'])) {
    $msg = '';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($email)) {
        $msg = "กรุณากรอกอีเมล";
    } else if ($password != $confirm) {
        $msg = "กรุณากรอกรหัสผ่านให้ตรงกัน";
    }
    if (empty($msg)) {

        if (account_activation) {
            if ($row['email'] == $email) {
                $activation_code = $row['activation_code'];
            } else {
                $activation_code = uniqid();
            }
        } else {
            $activation_code = $row['activation_code'];
        }
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $password = $row['password'];
        }
        $update_stmt = $db->prepare("UPDATE users SET email = :email, password = :password, activation_code = :activation_code WHERE user_id = :user_id");
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':password', $password);
        $update_stmt->bindParam(':activation_code', $activation_code);
        $update_stmt->bindParam(':user_id', $user_id);
        $update_stmt->execute();
        $_SESSION['email'] = $email;

        if (account_activation && $row['email'] != $email) {
            send_email($email, $activation_code);
            $msg = "คุณได้ทำการเปลี่ยนอีเมล กรุณายืนยันอีเมล";
            unset($_SESSION['is_logged_in']);
        } else {
            header("location: profile.php?email={$_SESSION['email']}");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background-color: #F3F4F7;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">DevMark</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
                    </li>
                    <?php if ($_SESSION['role'] == "Admin") : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php"><i class="fas fa-user-circle"></i> Admin</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-5">Profile Page</h1>
                <?php if (isset($msg)) : ?>
                    <div class="alert alert-danger alert-custom" role="alert">
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>
                <div class="card mt-5">
                    <div class="card-body" style="line-height: 2.5;">
                        <h4>รายละเอียดบัญชี</h4>

                        <form <?php if (!isset($_SESSION['is_logged_in'])) {
                                    echo "action='login.php'";
                                } else {
                                    echo "action=''";
                                } ?> method="post">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="exampleInputPassword1">
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>