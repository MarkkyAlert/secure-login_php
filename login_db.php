<?php
include('main.php');  
// ตรวจสอบการกด submit form
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // ตรวจสอบอีเมลและรหัสผ่าน
    if (empty($email) || empty($password)) {
        // ถ้าเป็นค่าว่่าง เก็บข้อความแจ้งเตือนใส่ session และ redirect ไปหน้า login
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('location: login.php');
        exit;
    } 
    else {
        // ถ้าไม่เป็นค่าว่าง ทำการตรวจสอบข้อมูลใน database ที่มีอีเมลตรงกันกับฟอร์ม
        $select_stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $select_stmt->bindParam(':email', $email);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // ถ้าไม่พบอีเมลที่ตรงกัน(ไม่มีบัญชีนี้ในระบบ) เก็บข้อความแจ้งเตือนใส่ session และ redirect ไปหน้า login
            $_SESSION['err_email'] = "ไม่มี email นี้ในระบบ";
            header('location: login.php');
            exit;
        }
        else {
            // ถ้ามีอีเมลตรงกัน(มีบัญชีนี้ในระบบ) ทำการ verify password
            if (password_verify($password, $row['password'])) {
                // ตรวจสอบสถานะการยืนยันบัญชี กรณีที่มีการยืนยันแล้ว
                if ($row['activation_code'] == "activated") {
                    // ถ้าผู้ใช้ทำการเลือก "จดจำฉัน" ในหน้าฟอร์ม
                    if (isset($_POST['remember'])) {
                        // ตรวจสอบจาก database ว่าผู้ใช้เคยกดจดจำมาก่อนหรือไม่
                        if ($row['remember'] == '') {
                            // ถ้าไม่เคย จะทำการ hash
                            $hash_cookie = password_hash($row['user_id'] . $row['email'], PASSWORD_DEFAULT);
                        }
                        else {
                            // ถ้าเคย จะใช้ค่าเดิม
                            $hash_cookie = $row['remember'];
                        }
                        
                        $days = 30; // กำหนดระยะเวลาในการจดจำการเข้าสู่ระบบ สามารถแก้ไขได้ตามต้องการ
                        // ทำการเซ็ต cookie แล้วอัปเดตลง database
                        setcookie('remember', $hash_cookie, (int)(time()+60*60*24*$days));
                        $update_stmt = $db->prepare("UPDATE users SET remember = :hash_cookie WHERE email = :email");
                        $update_stmt->bindParam(':hash_cookie', $hash_cookie);
                        $update_stmt->bindParam(':email', $email);
                        $update_stmt->execute();
                    }
                    // เก็บค่า email, user_id(ใช้ระบุผู้ใช้), role(เป็นผู้ใช้หรือแอดมิน), is_logged_in(เก็บสถานะการ login) ไว้ในตั้วแปรเซสชันเพื่อใช้ในหน้าต่อไป
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['is_logged_in'] = true;
                    header('location: index.php');
                    exit;
                }
                // ตรวจสอบสถานะการยืนยันบัญชี กรณีที่ยังไม่มีการยืนยัน
                else {
                    $_SESSION['activation_msg'] = "กรุณายืนยันอีเมล";
                    header('location: login.php');
                    exit;
                }
            }
            // ถ้าอีเมลไม่ตรงกัน(ไม่มีบัญชีนี้ในระบบ) จะแสดงข้อความเตือน
            else {
                $_SESSION['err_pw'] = "รหัสผ่านไม่ถูกต้อง";
                header('location: login.php');
                exit;
            }
        }
    }
}