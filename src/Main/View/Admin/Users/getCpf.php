<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Controller\Admin\Users\Controller\GetCpf;

if (!empty($_POST['cpf'])) {
    if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
        $cpfSearch = new GetCpf;
        $code = "";
        if(!empty($_POST['code']) && $_POST['code'] !== "" && $_POST['code'] !== null){
           if($_POST['code'] !== "undefined"){
            $code = $_POST['code'];
            }
        }
        echo ($cpfSearch->getCpfRegistered($_POST['cpf'],$code))? 1 : 0;
    }
}