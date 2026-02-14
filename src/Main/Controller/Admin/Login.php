<?php

use Microfw\Src\Main\Controller\Admin\Login\ProcessLogin;
use Microfw\Src\Main\Controller\Admin\Login\ExpelsVisitor;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ProcessLogin::processLogin($_POST['email'], $_POST['p'], $_POST['g-recaptcha'], $_POST['language']);
    exit;
}
// GET
if (isset($_SESSION['user_id'], $_SESSION['user_username'], $_SESSION['user_login_string'])) {
    ExpelsVisitor::expelsVisitor();
}
//require $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Admin/login.php';
