<?php
session_start();
include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {   
    $db = new PDO("mysql:host=" . db_host . ";dbname=" . db_name, db_user, db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {   
    echo "Failed to connect" . $e->getMessage();
}

function check_login($db, $redirect) {
    if (isset($_COOKIE['remember']) && $_COOKIE['remember'] != '') {
        $select_stmt = $db->prepare("SELECT * FROM users WHERE remember = :remember");
        $select_stmt->bindParam(':remember', $_COOKIE['remember']);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
        }
        else {
            header("location: $redirect");
            exit;
        }
    }
    else if (!isset($_SESSION['is_logged_in'])) {
        header("location: $redirect");
        exit;
    }
    
}

function is_not_admin($role, $redirect) {
    if ($role != 'Admin') {
        header("location: $redirect");
    }
}

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

function send_email($email, $activation_code) {
    header('Content-Type: text/html; charset=utf-8');
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    // ต้องขอการเข้าถึงจาก google ที่ https://www.google.com/settings/security/lesssecureapps

    $mail->Username = mail_from;
    $mail->Password = mail_password;
    $mail->setFrom(mail_from, company_name);
    $mail->addAddress($email);
    $mail->Subject = "กรุณายืนยันอีเมลของท่าน";
    $email_content = template_email(activation_link, $email, $activation_code, "ยืนยันอีเมล");
    $email_receiver = $email;

    if ($email_receiver) {
        $mail->msgHTML($email_content);
        $mail->send();
    }
}

function send_reset_link($email, $uniqid) {
    header('Content-Type: text/html; charset=utf-8');
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    // ต้องขอการเข้าถึงจาก google ที่ https://www.google.com/settings/security/lesssecureapps

    $mail->Username = mail_from;
    $mail->Password = mail_password;
    $mail->setFrom(mail_from, company_name);
    $mail->addAddress($email);
    $mail->Subject = "เปลี่ยนรหัสผ่าน";
    $email_content = template_email(reset_link, $email, $uniqid, "เปลี่ยนรหัสผ่าน");
    $email_receiver = $email;

    if ($email_receiver) {
        $mail->msgHTML($email_content);
        $mail->send();
    }
}





