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
        $msg = "ทำการยืนยันบัญชีเรียบร้อย กรุณาเข้าสู่ระบบ <br><a href='login.php'>เข้าสู่ระบบ</a>";
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
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
		<div class="content">
			<p><?php echo $msg; ?></p>
		</div>
	</body>
</html>