<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-background-purple">

    <div class="flex-login-form">

        <h1 class="text-white mb-5">สมัครสมาชิก</h1>

        <?php if (isset($_SESSION['err_fill'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['err_fill']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['err_pw'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['err_pw']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['exist_email'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['exist_email']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['err_insert'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['err_insert']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['sendmail_success'])) : ?>
            <div class="alert alert-success alert-custom" role="alert">
                <?php echo $_SESSION['sendmail_success']; ?>
            </div>
        <?php endif; ?>

        <form class="p-5 card login-card-custom" action="register_db.php" method="post">

            <div class="form-outline mb-3">
                <label class="form-label" for="email">อีเมล</label>
                <input type="email" name="email" id="email" class="form-control" required/>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label" for="password">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="form-control" required/>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label" for="confirm_password">ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirm_password" id="password" class="form-control" required/>
            </div>

            <div class="row">
                <p class="text-center">เป็นสมาชิกอยู่แล้ว ? <a href="login.php">เข้าสู่ระบบ</a></p>
            </div>

            <button type="submit" class="btn login-btn-purple btn-block text-white" name="submit">สมัครสมาชิก</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>

</html>

<?php
    if (isset($_SESSION['err_fill']) || isset($_SESSION['err_pw']) || isset($_SESSION['exist_email']) || isset($_SESSION['err_insert']) || isset($_SESSION['sendmail_success'])) {
        unset($_SESSION['err_fill']);
        unset($_SESSION['err_pw']);
        unset($_SESSION['exist_email']);
        unset($_SESSION['err_insert']);
        unset($_SESSION['sendmail_success']);
    }
?>