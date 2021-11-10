<?php 
include('main.php');

if (isset($_POST['submit'])) {
    if (isset($_POST['email'])) {
        $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND activation_code != '' AND activation_code != 'activated'");
        $select_stmt->bindParam(':email', $_POST['email']);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            send_email($_POST['email'], $row['activation_code']);
            $msg_suc = "ส่งอีเมลเรียบร้อยแล้ว กรุณาทำการยืนยัน";
        }
        else {
            $msg_err = "ไม่พบอีเมลนี้ในระบบ";
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
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-background-blue">

    <div class="flex-login-form">

        <h1 class="text-white mb-5">ส่งข้อความยืนยันอีกครั้ง</h1>

        <?php if (isset($msg_suc)) : ?>
            <div class="alert alert-success alert-custom" role="alert">
                <?php echo $msg_suc; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($msg_err)) : ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo $msg_err; ?>
            </div>
        <?php endif; ?>
        

        <form class="p-5 card login-card-custom" action="" method="post">
            <div class="form-outline mb-3">
                <label class="form-label" for="email">อีเมล</label>
                <input type="email" name="email" id="email" class="form-control" required />
            </div>

            <button type="submit" name="submit" class="btn login-btn-blue btn-block text-white">ส่ง</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
