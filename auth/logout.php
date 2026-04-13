<?php
require_once '../config.php';
requireLogin();
verifyCsrf();
session_destroy();
if (isset($_COOKIE['remember_acahub'])) {
    setcookie('remember_acahub', '', time() - 3600, '/');
}
header('Location: login.php');
exit;
