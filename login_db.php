<?php
session_start();  
include('main.php');  

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('location: login.php');
        exit;
    } 
    else {
        $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $select_stmt->bindParam(':email', $email);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION['err_email'] = "ไม่มี email นี้ในระบบ";
            header('location: login.php');
            exit;
        }

        else {
            if (password_verify($password, $row['password'])) {
               
                if ($row['activation_code'] == "activated") {

                    if (isset($_POST['remember'])) {

                        if ($row['remember'] == '') {
                            $hash_cookie = password_hash($row['user_id'] . $row['email'], PASSWORD_DEFAULT);
                        }
                        else {
                            $hash_cookie = $row['remember'];
                        }
                        $days = 30;
                        setcookie('remember', $hash_cookie, (int)(time()+60*60*24*$days));
                        $update_stmt = $db->prepare("UPDATE users SET remember = :hash_cookie WHERE email = :email");
                        $update_stmt->bindParam(':hash_cookie', $hash_cookie);
                        $update_stmt->bindParam(':email', $email);
                        $update_stmt->execute();
                    }
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['is_logged_in'] = true;
                    header('location: index.php');
                    exit;
                }
                else {
                    $_SESSION['activation_msg'] = "กรุณายืนยันอีเมล";
                    header('location: login.php');
                    exit;
                }
            }
            else {
                $_SESSION['err_pw'] = "รหัสผ่านไม่ถูกต้อง";
                header('location: login.php');
                exit;
            }
        }
    }
}