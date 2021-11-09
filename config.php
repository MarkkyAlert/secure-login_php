<?php
if (!isset($_SESSION['config'])) {
define('db_host', 'localhost');
define('db_user', 'root');
define('db_pass', '');
define('db_name', 'secure_login');
define('account_activation', true);
define('mail_from', 'puettipong.o@gmail.com');
define('mail_password', 'Appleid13');
define('company_name', 'MARKPRUET');
define('activation_link', 'http://localhost/secure-login_php/activate.php');
date_default_timezone_set("Asia/Bangkok");
}