<?php
session_start();  // เขียนทุกครั้งที่มีการใช้ตัวแปร session
include('connection.php');  // นำเข้าไฟล์ database

// ทำการเช็คว่ามีการ submit form หรือไม่ isset() จะเช็คว่ามี data หรือไม่
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ถ้าไม่มีการกรอกข้อมูลเข้ามาให้ทำการส่งข้อความกลับไปยังหน้า login.php
    if (empty($email) || empty($password)) {
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('location: login.php');
    } 

    // กรณีที่กรอกข้อมูลครบถ้วนจะทำการ query ข้อมูล เพื่อเช็คว่ามี email นี้อยู่ในระบบหรือไม่
    else {
        // query ข้อมูล เพื่อเช็คว่ามี email นี้อยู่ในระบบหรือไม่
        $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $select_stmt->bindParam(':email', $email);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        // ถ้ามี email ในระบบให้ทำการส่งข้อความกลับไปยังหน้า login.php
        if (!$row) {
            $_SESSION['err_email'] = "ไม่มี email นี้ในระบบ";
            header('location: login.php');
        }

        // ถ้าไม่พบ email จะทำการตรวจสอบ password โดยเทียบ password ที่กรอกเข้ามาตรงกับ password ใน database หรือไม่ ผ่านฟังก์ชัน password_verify() ถ้าตรงกันเงื่อนไขจะเป็นจริง
        else {
            // ถ้า password ที่กรอกเข้ามาตรงกับ password ใน database
            if (password_verify($password, $row['password'])) {
                // เก็บ email และ สถานะ login และไปยังหน้า index.php
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
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['is_logged_in'] = true;
                    header('location: index.php');
                }
               else {
                   $_SESSION['activation_msg'] = "กรุณายืนยันอีเมล";
                   header('location: login.php');
               }
                
            }

            // ถ้า password ที่กรอกเข้ามาไม่ตรงกับ password ใน database
            else {
                $_SESSION['err_pw'] = "รหัสผ่านไม่ถูกต้อง";
                header('location: login.php');
            }
        }
    }
}
