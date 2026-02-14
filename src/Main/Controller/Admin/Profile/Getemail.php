<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Controller\Admin\Profile\Controller\GetEmail;

if (!empty($_POST['email'])) {
    if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
        $emailSearch = new GetEmail;
        echo ($emailSearch->getEmailRegistered($_POST['email'])) ? 1 : 0;
    }
}