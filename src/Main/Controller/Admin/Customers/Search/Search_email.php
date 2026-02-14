<?php

use Microfw\Src\Main\Controller\Admin\Customers\GetEmail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email'])) {
        if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
            // executa automaticamente
            echo ((new GetEmail)->getEmailRegistered($_POST['email'])) ? 1 : 0;
            exit;
        }
    }
} else {
    $config = new McConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
    exit;
}