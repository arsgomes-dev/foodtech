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
    '/app/Notificationpayment',
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
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();
