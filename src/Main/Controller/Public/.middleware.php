<?php

session_start();

// URI atual
$uri = $_SERVER['REQUEST_URI'] ?? '';

// 🔓 ROTAS LIBERADAS (SEM LOGIN)
$publicRoutes = [
    '/app/login',
    '/app/recovery',
    '/app/login/ProcessLogin',
    '/app/Oauth2logincallback',
    '/app/oauth2Logincallback',
    '/app/notificationpayment',
    '/panel/login',
    '/panel/login/ProcessLogin',
];
// se a rota atual for pública, não bloqueia
foreach ($publicRoutes as $route) {
    if (str_starts_with($uri, $route)) {
        return;
    }
}

// 🔐 PROTEÇÃO NORMAL
use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();
/*
use Microfw\Src\Main\Controller\Public\Home;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$planService = new CheckPlan;
$check = $planService->checkPlan();

$freeRoutes = [
    '/app/login',
    '/app/recovery',
    '/app/login/ProcessLogin',
    '/app/Oauth2logincallback',
    '/app/oauth2Logincallback',
    '/app/paymentPlan',
    '/app/renewPlan',
    '/app/home',
    '/app/keywords',
    '/app/ytoptimization',
    '/app/scripts',
    '/app/channels',
    '/app/channels',
    '/app/kanban'
];

foreach ($freeRoutes as $route) {
    if (!str_starts_with($uri, $route)) {
        if (!$check['allowed']) {
            header('Location: /app');
            exit;
        }
    }
}*/