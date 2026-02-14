<?php

use Microfw\Src\Main\Controller\Public\Profile\GetEmail;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig;
$planService = new CheckPlan;
$check = $planService->checkPlan();
if (!$check['allowed']) {
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email'])) {
        if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
            // executa automaticamente
            echo ((new GetEmail)->getEmailRegistered($_POST['email'])) ? 1 : 0;
            exit;
        }
    }
} else {
    $config = new McClientConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}