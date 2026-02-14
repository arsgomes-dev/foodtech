<?php

namespace Microfw\Src\Main\Controller\Public\Calendars;

use Microfw\Src\Main\Controller\Public\View\View;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig;
$planService = new CheckPlan;
$check = $planService->checkPlan();
if (!$check['allowed']) {
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}

class Events {

    public function events(array $params) {
// Extrai os parâmetros GET com defaults
        $gets = [
            'start' => $params['start'] ?? null,
            'end' => $params['end'] ?? null,
            'month' => $params['month'] ?? null,
            'year' => $params['year'] ?? null,
        ];

// Passa para a view
        View::render('Calendars/events', [
            'gets' => $gets
        ]);
    }
}
