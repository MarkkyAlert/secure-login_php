<?php
session_start();
require('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {   //ทำการเชื่อมต่อ database
    $db = new PDO("mysql:host=" . db_host . ";dbname=" . db_name, db_user, db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {   //หากเชื่อมต่อผิดพลาดให้แสดงข้อความเตือน
    echo "Failed to connect" . $e->getMessage();
}

function check_login($db) {
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
            header('location: login.php');
        }
    }
    else if (!isset($_SESSION['is_logged_in'])) {
        header('location: login.php');
    }
    
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

    $mail->Username = "puettipong.o@gmail.com";
    $mail->Password = "Appleid13";
    $mail->setFrom("puettipong.o@gmail.com", "MARKPRUET");
    $mail->addAddress($email);
    $mail->Subject = "กรุณายืนยันอีเมล์ของท่าน";
    $email_content = activation_link . '?email=' . $email . '&code=' . $activation_code;

    $email_receiver = $email;

    if ($email_receiver) {
        $mail->msgHTML($email_content);
        $mail->send();
    }
}




