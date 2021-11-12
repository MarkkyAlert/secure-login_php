<?php
include('main.php');

if (isset($_GET['email']) && isset($_GET['code']) && !empty($_GET['code'])) {
    $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND reset = :code");
    $select_stmt->bindParam(':email', $_GET['email']);
    $select_stmt->bindParam(':code', $_GET['code']);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
            if ($_POST['password'] != $_POST['confirm_password']) {
                $msg_err = "รหัสผ่านไม่ตรงกัน";
            }
            else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $update_stmt = $db->prepare("UPDATE users SET password = :password, reset = '' WHERE email = :email");
                $update_stmt->bindParam(':password', $password);
                $update_stmt->bindParam(':email', $_GET['email']);
                $update_stmt->execute();
                $msg_suc = "เปลี่ยนรหัสผ่านสำเร็จ <a href='login.php'>เข้าสู่ระบบ</a>";
            }
        }
    }
    else {
        $msg_err = "ไม่พบลิงก์ดังกล่าว กรุณาส่งอีเมลเพื่อรับลิงก์เปลี่ยนรหัสผ่านอีกครั้ง";
    }
}
else {
    header('location: 404page.html');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปลี่ยนรหัสผ่าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-background-purple">

    <div class="flex-login-form">

        <h1 class="text-white mb-5">เปลี่ยนรหัสผ่าน</h1>

        <?php if (isset($msg_err)) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $msg_err; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($msg_suc)) : ?>
            <div class="alert alert-success alert-custom" role="alert">
                <?php echo $msg_suc; ?>
            </div>
        <?php endif; ?>
        
        <form class="p-5 card login-card-custom" action="" method="post">
            <div class="form-outline mb-3">
                <label class="form-label" for="password">รหัสผ่านใหม่</label>
                <input type="password" name="password" id="password" class="form-control" required/>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label" for="confirm_password">ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required/>
            </div>

            <button type="submit" name="submit" class="btn login-btn-purple btn-block text-white">ยืนยัน</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>