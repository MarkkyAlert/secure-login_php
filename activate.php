<?php
include('main.php');

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $activation_code = $_GET['code'];
    $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND activation_code = :code");
    $select_stmt->bindParam(':email', $email);
    $select_stmt->bindParam(':code', $activation_code);
    $select_stmt->execute();
    $user = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $update_stmt = $db->prepare("UPDATE users SET activation_code = 'activated' WHERE email = :email");
        $update_stmt->bindParam(':email', $email);
        $update_stmt->execute();
        $msg = "ทำการยืนยันบัญชีเรียบร้อย กรุณาเข้าสู่ระบบ <br><a href='login.php' style='color: white;'>เข้าสู่ระบบ</a>";
    }
    else {
        $msg = "บัญชีของคุณได้ทำการยืนยันไปแล้ว";
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Activate Account</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap');

            body {
                font-family: 'Kanit', sans-serif;
            }
            p {
                font-size: 20px;
            }
        </style>
    </head>
	<body>
        <div class="bg-secondary p-5">
            <div class="container">
                <p class="text-center text-white"><?php echo $msg; ?></p>
            </div>
        </div>
		
	</body>
</html>