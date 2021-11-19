<?php
// ไฟล์ main.php ประกอบด้วย การเชื่อมต่อ database, การกำหนด session และ function การทำงานต่างๆ
// เราจะมีการใช้ session ดังนั้นจึงต้องทำการ start session ดังโค้ดด้านล่าง
session_start();
// นำเข้าไฟล์ config.php
include('config.php');
// เรียกใช้ PHPMailer สำหรับการส่งเมล
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {   
    // ทำการเชื่อมต่อ database
    $db = new PDO("mysql:host=" . db_host . ";dbname=" . db_name, db_user, db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {   
    // หากเชื่อมต่อไม่สำเร็จ จะหยุดการทำงานและแสดง error
    echo "Failed to connect" . $e->getMessage();
}
// ทำการตรวจสอบการ login และ ตรวจสอบ remember me cookie
function check_login($db, $redirect) {
    // ตรวจสอบ remember cookie
    if (isset($_COOKIE['remember']) && $_COOKIE['remember'] != '') {
        // ถ้า remember cookie ตรงกันกับใน database เราจะทำการอัปเดตตัวแปร session
        $select_stmt = $db->prepare("SELECT * FROM users WHERE remember = :remember");
        $select_stmt->bindParam(':remember', $_COOKIE['remember']);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // ถ้าตรงกันจะทำการอัปเดตตัวแปร session และเก็บสถานะการ login
            $_SESSION['is_logged_in'] = true;
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
        }
        else {
            // ถ้าไม่ตรงกันจะ redirect ไปยังหน้า login
            header("location: $redirect");
            exit;
        }
    }
    else if (!isset($_SESSION['is_logged_in'])) {
        // ถ้าผู้ใช้ไม่ได้มีการ login ให้ redirect ไปยังหน้า login
        header("location: $redirect");
        exit;
    }
    
}
// ทำการตรวจสอบว่าเป็นแอดมินหรือไม่
function is_not_admin($role, $redirect) {
    if ($role != 'Admin') {
        // ถ้าไม่ใช่แอดมิน ให้ redirect ไปยังหน้า not found
        header("location: $redirect");
    }
}
// รูปแบบการแสดงผลข้อความในอีเมล
function template_email($link, $email, $activation_code, $heading) {
    $activation_link = $link . '?email=' . $email . '&code=' . $activation_code;
    return <<<EOT
    <!DOCTYPE html>
    <html>
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
    </head>
    
    <body style="font-family:-apple-system, BlinkMacSystemFont, "segoe ui", roboto, oxygen, ubuntu, cantarell, "fira sans", "droid sans", "helvetica neue", Arial, sans-serif;box-sizing:border-box;font-size:16px;">
        <div style="background-color:#F5F6F8;">
            <div style="padding:60px;background-color:#fff;margin:60px;text-align:center;box-sizing:border-box;font-size:16px;">
                <h1 style="box-sizing:border-box;font-size:18px;color:#474a50;padding-bottom:10px;">$heading</h1>
                <p style="box-sizing:border-box;font-size:16px;">คลิก <a href="$activation_link"
                        style="text-decoration:none;color:#c52424;box-sizing:border-box;font-size:16px;">ที่นี่</a>
                    เพื่อทำการ$heading</p>
            </div>
        </div>
    </body>
    
    </html>
    EOT;
}
// การส่งเมลเพืื่อยืนยันบัญชี
function send_email($email, $activation_code) {
    header('Content-Type: text/html; charset=utf-8');
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    // ทำการเปลี่ยน host กรณีไม่ใช่ gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    // กรณีใช้ gmail ต้องขอการเข้าถึงจาก google ที่ https://www.google.com/settings/security/lesssecureapps
    // เซ็ตค่าตัวแปรจากไฟล์ config
    $mail->Username = mail_from;
    $mail->Password = mail_password;
    $mail->setFrom(mail_from, company_name);
    // เซ็ต address จากการส่งค่าผ่าน parameter
    $mail->addAddress($email);
    // หัวข้อของข้อความ สามารถเปลี่ยนได้ตามต้องการ
    $mail->Subject = "กรุณายืนยันอีเมลของท่าน";
    $email_content = template_email(activation_link, $email, $activation_code, "ยืนยันอีเมล");
    $email_receiver = $email;

    if ($email_receiver) {
        // ถ้ามีอีเมลผู้รับ จะทำการส่งเมล
        $mail->msgHTML($email_content);
        $mail->send();
    }
}
// การส่งเมลเพื่อรับลิงก์ในการรีเซ็ตรหัสผ่าน
function send_reset_link($email, $uniqid) {
    header('Content-Type: text/html; charset=utf-8');
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    // ทำการเปลี่ยน host กรณีไม่ใช่ gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    // ต้องขอการเข้าถึงจาก google ที่ https://www.google.com/settings/security/lesssecureapps
    // เซ็ตค่าตัวแปรจากไฟล์ config
    $mail->Username = mail_from;
    $mail->Password = mail_password;
    $mail->setFrom(mail_from, company_name);
    // เซ็ต address จากการส่งค่าผ่าน parameter
    $mail->addAddress($email);
    // หัวข้อของข้อความ สามารถเปลี่ยนได้ตามต้องการ
    $mail->Subject = "เปลี่ยนรหัสผ่าน";
    $email_content = template_email(reset_link, $email, $uniqid, "เปลี่ยนรหัสผ่าน");
    $email_receiver = $email;

    if ($email_receiver) {
        // ถ้ามีอีเมลผู้รับ จะทำการส่งเมล
        $mail->msgHTML($email_content);
        $mail->send();
    }
}





