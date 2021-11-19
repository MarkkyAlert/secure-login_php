<?php
// database hostname
define('db_host', 'localhost');
// database username
define('db_user', 'root');
// database password
define('db_pass', '');
// database name
define('db_name', 'secure_login');
// ถ้าไม่ต้องการให้มีการยืนยันบัญชี เปลี่ยนเป็น false
define('account_activation', true);
// ตั้งค่า อีเมล, รหัสผ่านอีเมล, ชื่อบริษัท
define('mail_from', 'youremail@yourdomain.com');
define('mail_password', 'yourpassword');
define('company_name', 'your company name');
// ลิงก์สำหรับการยืนยันบัญชี
define('activation_link', 'http://yourdomain.com/activate.php');
// ลิงก์สำหรับการรีเซ็ตรหัสผ่าน
define('reset_link', 'http://yourdomain.com/resetpassword.php');
// ตั้งค่า time zone
date_default_timezone_set("Asia/Bangkok");
