<?php

use Microfw\Src\Main\Controller\Public\Login\ProcessLogin;
use Microfw\Src\Main\Controller\Public\Login\ExpelsVisitor;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ProcessLogin::processLogin($_POST['email'], $_POST['p'], $_POST['g-recaptcha'], $_POST['language']);
    exit;
}
// GET
if (isset($_SESSION['client_id'], $_SESSION['client_username'], $_SESSION['client_login_string'])) {
    ExpelsVisitor::expelsVisitor();
}
require $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Public/login.php';
