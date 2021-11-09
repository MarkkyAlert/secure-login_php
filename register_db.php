<?php
session_start();    
include('main.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('location: register.php');
        exit;
    }
    else {
        if ($password !== $confirm_password) {
            $_SESSION['err_pw'] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            header('location: register.php');
            exit;
        }
        else {
            $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $select_stmt->bindParam(':email', $email);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
           
            if ($row) {
                $_SESSION['exist_email'] = "มี email นี้ในระบบ";
                header('location: register.php');
                exit;
            }
            else {
                $password = password_hash($password, PASSWORD_DEFAULT);
                
                if (account_activation) {
                    $activation_code = uniqid();
                } 
                else {
                    $activation_code = "activated";
                }
                $insert_stmt = $db->prepare("INSERT INTO users (email, password, activation_code, role) VALUES (:email, :password, :activation_code, 'Member')");
                $insert_stmt->bindParam(':email', $email);
                $insert_stmt->bindParam(':password', $password);
                $insert_stmt->bindParam(':activation_code', $activation_code);
                $insert_stmt->execute();

                if ($insert_stmt) {
                    if (account_activation) {
                        send_email($email, $activation_code);
                        $_SESSION['sendmail_success'] = "ระบบได้ส่งลิงก์ยืนยันไปที่เมลของท่าน กรุณาทำการยืนยัน";
                        header('location: register.php');
                        exit;
                    } 
                    else {
                        $_SESSION['email'] = $email;
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['is_logged_in'] = true;
                        $_SESSION['role'] = $row['role'];
                        header('location: index.php');
                        exit;
                    }
                }
                else {
                    $_SESSION['err_insert'] = "ไม่สามารถนำเข้าข้อมูลได้";
                    header('location: register.php');
                    exit;
                }
            }
        }
    }
}
