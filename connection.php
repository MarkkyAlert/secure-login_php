<?php

$db_host = "localhost"; 
$db_user = "root";     
$db_password = "";
$db_name = "secure_login";
date_default_timezone_set("Asia/Bangkok");

try {   //ทำการเชื่อมต่อ database
    $db = new PDO("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {   //หากเชื่อมต่อผิดพลาดให้แสดงข้อความเตือน
    echo "Failed to connect" . $e->getMessage();
}