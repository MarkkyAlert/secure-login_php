<?php
include('main.php');
// ตรวจสอบการกด submit form
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($email) || empty($password) || empty($confirm_password)) {
        // ตรวจสอบอีเมลและรหัสผ่าน กรณีที่ไม่มีการกรอกข้อมูลเข้ามา
        // เก็บข้อความแจ้งเตือนใส่ session และ redirect ไปหน้า register
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('location: register.php');
        exit;
    }
    else {
        // ตรวจสอบอีเมลและรหัสผ่าน กรณีที่มีการกรอกข้อมูลเข้ามาครบถ้วน
        if ($password !== $confirm_password) {
            // ทำการตรวจสอบรหัสผ่าน กรณีรหัสผ่านไม่ตรงกัน 
            // เก็บข้อความแจ้งเตือนใส่ session และ redirect ไปหน้า register
            $_SESSION['err_pw'] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            header('location: register.php');
            exit;
        }
        else {
            // ทำการตรวจสอบรหัสผ่าน กรณีรหัสผ่านตรงกัน 
            // ตรวจสอบข้อมูลจาก database ว่ามีอีเมลนี้ในระบบหรือไม่
            $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $select_stmt->bindParam(':email', $email);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                // กรณีมีอีเมลในระบบ (แสดงว่ามีอีเมลซ้ำ)
                // เก็บข้อความแจ้งเตือนใส่ session และ redirect ไปหน้า register
                $_SESSION['exist_email'] = "มี email นี้ในระบบ";
                header('location: register.php');
                exit;
            }
            else {
                // กรณีมีอีเมลในระบบ (แสดงว่าไม่มีอีเมลซ้ำ)
                // ทำการ hash password
                $password = password_hash($password, PASSWORD_DEFAULT);
                
                if (account_activation) {
                    // กรณีที่กำหนดให้มีการยืนยันบัญชี จะกำหนดโค้ดสำหรับการยืนยันไว้ในตัวแปร
                    $activation_code = uniqid();
                } 
                else {
                    // กรณีที่กำหนดให้ไม่มีการยืนยันบัญชี จะกำหนดให้เป็น "activated" 
                    $activation_code = "activated";
                }
                // เพิ่มข้อมูลลงใน database
                $insert_stmt = $db->prepare("INSERT INTO users (email, password, activation_code, role) VALUES (:email, :password, :activation_code, 'Member')");
                $insert_stmt->bindParam(':email', $email);
                $insert_stmt->bindParam(':password', $password);
                $insert_stmt->bindParam(':activation_code', $activation_code);
                $insert_stmt->execute();

                if ($insert_stmt) {
                    if (account_activation) {
                        // กรณีที่กำหนดให้มีการยืนยันบัญชี จะทำการส่งเมลไปยังผู้ใช้
                        send_email($email, $activation_code);
                        $_SESSION['sendmail_success'] = "ระบบได้ส่งลิงก์ยืนยันไปที่เมลของท่าน กรุณาทำการยืนยัน";
                        header('location: register.php');
                        exit;
                    } 
                    else {
                        // กรณีที่กำหนดให้ไม่มีการยืนยันบัญชี จะทำการเก็บค่าข้อมูลสถานะต่างๆ
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
