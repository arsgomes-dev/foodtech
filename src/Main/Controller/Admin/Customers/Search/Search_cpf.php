<?php

use Microfw\Src\Main\Controller\Admin\Customers\GetCpf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cpf'])) {
        if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
            // executa automaticamente
            echo ((new GetCpf)->getCpfRegistered($_POST['cpf'])) ? 1 : 0;
            exit;
        }
    }
} else {
    $config = new McConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
    exit;
}

