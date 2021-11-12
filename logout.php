<?php
session_start();
session_destroy();

if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']);
    setcookie('remember', '');
}
header('Location: login.php');
