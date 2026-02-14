<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Controller\Admin\Profile\Controller\GetCpf;

if (!empty($_POST['cpf'])) {
    if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
        $cpfSearch = new GetCpf;
        echo ($cpfSearch->getCpfRegistered($_POST['cpf']))? 1 : 0;
    }
}