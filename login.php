<?php 
session_start(); 

if (isset($_SESSION['is_logged_in'])) {
    if ($_SESSION['role'] == 'Admin') {
        header('location: admin/index.php');
    }
    else {
        header('location: index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-background-purple">

    <div class="flex-login-form">

        <h1 class="text-white mb-5">เข้าสู่ระบบ</h1>

        <!-- ข้อความแจ้งเตือนจากหน้า login_db.php ที่มาจากตัวแปรเซสชัน -->
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
        <?php if (isset($_SESSION['err_email'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['err_email']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['activation_msg'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['activation_msg']; ?> หากไม่ได้รับข้อความ <a href="resend_email.php" style="color: red; text-decoration: none;"> คลิกที่นี่ </a>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['err_token'])) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $_SESSION['err_token']; ?>
            </div>
        <?php endif; ?>

        <form class="p-5 card login-card-custom" action="login_db.php" method="post">
            <div class="form-outline mb-3">
                <label class="form-label" for="email">อีเมล</label>
                <input type="email" name="email" id="email" class="form-control" required/>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label" for="password">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="form-control" required/>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">จดจำฉัน</label>
            </div>

            <div class="row">
                <p class="text-center">จำรหัสผ่านไม่ได้ ? <a href="forgotpassword.php">ลืมรหัสผ่าน</a></p>
            </div>

            <button type="submit" name="submit" class="btn login-btn-purple btn-block text-white">เข้าสู่ระบบ</button>

        </form>

        <div class="row mt-2">
            <p class="text-center text-white">ยังไม่เป็นสมาชิก ? <a href="register.php" style="color: #fff; font-weight: bold;">สมัครสมาชิก</a></p>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>

<?php
// ทำให้ข้อความเตือนหายไปเมื่อมีการรีเฟรชหน้า
if (isset($_SESSION['err_fill']) || isset($_SESSION['err_pw']) || isset($_SESSION['err_email']) || isset($_SESSION['activation_msg']) || isset($_SESSION['err_token'])) {
    unset($_SESSION['err_fill']);
    unset($_SESSION['err_pw']);
    unset($_SESSION['err_email']);
    unset($_SESSION['activation_msg']);
    unset($_SESSION['err_token']);
}
?>