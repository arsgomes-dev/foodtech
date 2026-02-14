<?php

use Microfw\Src\Main\Controller\Admin\Users\Controller\GetEmail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email'])) {
        if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
            $emailSearch = new GetEmail;
            $code = "";
            if (!empty($_POST['code']) && $_POST['code'] !== "" && $_POST['code'] !== null) {
                if ($_POST['code'] !== "undefined") {
                    $code = $_POST['code'];
                }
            }
            echo ($emailSearch->getEmailRegistered($_POST['email'], $code)) ? 1 : 0;
        }
    }
} else {
    $config = new McConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
    exit;
}