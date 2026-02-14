<?php

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
    require $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Public/Gemini/analyze.php';
    exit;
} else {
    $config = new McClientConfig;
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}
