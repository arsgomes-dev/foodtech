<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Controller\Admin\Customers\GetEmail;

if (!empty($_POST['email'])) {
    if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
        $emailSearch = new GetEmail;
        $code = "";
        if (!empty($_POST['code']) && $_POST['code'] !== "" && $_POST['code'] !== null) {
            if($_POST['code'] !== "undefined"){
            $code = $_POST['code'];
            }
        }
        echo ($emailSearch->getEmailRegistered($_POST['email'], $code)) ? 1 : 0;
    }
}