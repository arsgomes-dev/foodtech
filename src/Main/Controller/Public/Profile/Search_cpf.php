<?php

use Microfw\Src\Main\Controller\Public\Profile\GetCpf;
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
    if (!empty($_POST['cpf'])) {
        if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
            // executa automaticamente
            echo ((new GetCpf)->getCpfRegistered($_POST['cpf'])) ? 1 : 0;
            exit;
        }
    }
} else {
    $config = new McClientConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}

