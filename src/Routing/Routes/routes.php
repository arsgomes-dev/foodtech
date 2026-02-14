<?php

use Microfw\Src\Routing\Router\ValidateRoutes;
use Microfw\Src\Main\Controller\Public\Scripts\Script;
use Microfw\Src\Main\Controller\Public\Calendars\Events;

$routes = new ValidateRoutes;

$admin = env('URL_ADMIN') ?? 'panel';
$public = env('URL_PUBLIC') ?? 'app';

$adminFolder = env('FOLDER_ADMIN') ?? 'panel';
$publicFolder = env('FOLDER_PUBLIC') ?? 'app';

$router->get('/', fn() =>
        $routes->getRoutes('/Landing/index', 'notFound')
);
$router->get('/termos', fn() =>
        $routes->getRoutes('/Landing/termos', 'notFound')
);
$router->get('/privacidade', fn() =>
        $routes->getRoutes('/Landing/privacidade', 'notFound')
);
$router->post('/signatures', fn() =>
        $routes->getRoutes('/Landing/signatures', 'notFound')
);
$router->get('/signatures', fn() =>
        $routes->getRoutes('/Landing/signatures', 'notFound')
);
$router->get('/payment', fn() =>
        $routes->getRoutes('/Landing/security/payment', 'notFound')
);
$router->post('/payment', fn() =>
        $routes->getRoutes('/Landing/security/payment', 'notFound')
);
$router->post('/validatecoupon', fn() =>
        $routes->getRoutes('/Landing/security/validatecoupon', 'notFound')
);
$router->post('/eficredit', fn() =>
        $routes->getRoutes('/Landing/security/payment/efipay/creditcard', 'notFound')
);
/**
 * =========================
 * APP (PUBLIC)
 * =========================
 */
$router->get('/' . $public, fn() =>
        $routes->getRoutes($publicFolder . '/home', 'notFound')
);

$router->get('/' . $public . '/login', fn() =>
        $routes->getRoutes($publicFolder . '/login', 'notFound')
);
/*
  $router->post('/' . $public . '/login', fn() =>
  $routes->getRoutes($publicFolder . '/Login/Connect', 'notFound')
  );

  $router->get('/' . $public . '/login/logout', fn() =>
  $routes->getRoutes($publicFolder . '/Login/Logout', 'notFound')
  );
 */
$router->get('/' . $public . '/calendars/events?{id}', function () {
    $gets = [
        'start' => $_GET['start'] ?? null,
        'end' => $_GET['end'] ?? null,
        'month' => $_GET['month'] ?? null,
        'year' => $_GET['year'] ?? null,
    ];
    $scriptController = new Events;
    $scriptController->events($gets);
});
$router->get('/' . $public . '/scripts/script/{id}', function ($id) {
    $scriptController = new Script;
    $scriptController->page($id);
});
/**
 * =========================
 * PANEL (ADMIN)
 * =========================
 */
$router->get('/' . $admin, fn() =>
        $routes->getRoutes($adminFolder . '/home', 'notFound')
);

$router->get('/' . $admin . '/login', fn() =>
        $routes->getRoutes($adminFolder . '/login', 'notFound')
);

$router->post('/' . $admin . '/login', fn() =>
        $routes->getRoutes($adminFolder . '/Login/Connect', 'notFound')
);
$router->get('/' . $admin . '/login/logout', fn() =>
        $routes->getRoutes($adminFolder . '/Login/Logout', 'notFound')
);
$router->get('/' . $admin . '/accessplans/{id}', function ($id) {
    $planController = new Microfw\Src\Main\Controller\Admin\Accessplans\Search\AccessPlan;
    $planController->page($id);
});
$router->get('/' . $admin . '/coupons/{id}', function ($id) {
    $couponController = new Microfw\Src\Main\Controller\Admin\Coupons\Search\Coupon;
    $couponController->page($id);
});
$router->get('/' . $admin . '/customer/{id}', function ($id) {
    $customerController = new Microfw\Src\Main\Controller\Admin\Customers\Search\Customer();
    $customerController->page($id);
});
$router->get('/' . $admin . '/signatures/{id}', function ($id) {
    $signatureController = new Microfw\Src\Main\Controller\Admin\Signatures\Search\Signature();
    $signatureController->page($id);
});
$router->get('/' . $admin . '/ticket/{id}', function ($id) {
    $ticketsController = new Microfw\Src\Main\Controller\Admin\Tickets\Search\Ticket();
    $ticketsController->page($id);
});
$router->get('/' . $admin . '/tickets/department/{id}', function ($id) {
    $departmentController = new Microfw\Src\Main\Controller\Admin\Tickets\Departments\Department();
    $departmentController->page($id);
});
$router->get('/' . $admin . '/user/{id}', function ($id) {
    $userController = new Microfw\Src\Main\Controller\Admin\Users\Search\User();
    $userController->page($id);
});
$router->get('/' . $admin . '/department/{id}', function ($id) {
    $departmentController = new Microfw\Src\Main\Controller\Admin\Departments\Search\Department();
    $departmentController->page($id);
});
$router->get('/' . $admin . '/privilege/{id}', function ($id) {
    $privilegeController = new Microfw\Src\Main\Controller\Admin\Privileges\Search\Privilege();
    $privilegeController->page($id);
});

/*
use Microfw\Src\Routing\Router\ValidateRoutes;

$routes = new ValidateRoutes;

// =========================
 // APP (PUBLIC)
 //=========================
 
$router->get('/app', fn() =>
        $routes->getRoutes('Public/home', 'notFound')
);

$router->get('/app/login', fn() =>
        $routes->getRoutes('Public/login', 'notFound')
);

$router->post('/app/Login/ProcessLogin', fn() =>
        $routes->getRoutes('Public/Login/Connect', 'notFound')
);

$router->get('/app/login/logout', fn() =>
        $routes->getRoutes('Public/Login/Logout', 'notFound')
);


// Rotas públicas dinâmicas
 // /app/{view}
 // /app/{folder}/{view}
 
$router->get('/app/{view}', function ($view) use ($routes) {
    $routes->getRoutes(
            'Public/' . basename($view),
            'notFound'
    );
});
$router->post('/app/{view}', function ($view) use ($routes) {
    $routes->getRoutes(
            'Public/' . basename($view),
            'notFound'
    );
});
$router->get('/app/{folder}/{view}/{code}', function ($folder, $view, $code) use ($routes) {
    $gets = ["code" => $code];
    $routes->getRoutes(
            'Public/' . ucfirst(basename($folder)) . '/' . basename($view),
            'notFound', $gets
    );
});

$router->post('/app/list/{folder}/{view}', function ($folder, $view) use ($routes) {
    $routes->getRoutes(
            'Public/' . basename($folder) . '/' . basename($view),
            'notFound'
    );
});
$router->post('/app/http/{folder}/{view}', function ($folder, $view) use ($routes) {
    $routes->getHttp(
            basename($folder) . '/' . basename($view),
            'notFound'
    );
});
$router->post('/app/API/{version}/{folder}/{view}', function ($version, $folder, $view) use ($routes) {
    $routes->getRoutes(
            'Public/API/' . basename($version) . '/' . basename($folder) . '/' . basename($view),
            'notFound'
    );
});

 //* =========================
// * PANEL (ADMIN)
 //* =========================
 
$router->get('/panel', fn() =>
        $routes->getRoutes('Admin/home', 'notFound')
);

$router->get('/panel/login', fn() =>
        $routes->getRoutes('Admin/login', 'notFound')
);

$router->post('/panel/Login/ProcessLogin', fn() =>
        $routes->getRoutes('Admin/Login/Connect', 'notFound')
);

$router->get('/panel/login/logout', fn() =>
        $routes->getRoutes('Admin/Login/Logout', 'notFound')
);

$router->get('/panel/{view}', function ($view) use ($routes) {
    $routes->getRoutes(
            'Admin/' . basename($view),
            'notFound'
    );
});
$router->get('/panel/{folder}/{view}', function ($folder, $view) use ($routes) {
    $routes->getRoutes(
            'Admin/' . ucfirst(basename($folder)) . '/' . basename($view),
            'notFound'
    );
});

$router->get('/panel/{folder}/{view}/{code}', function ($folder, $view, $code) use ($routes) {
    $gets = ["code" => $code];
    $routes->getRoutes(
            'Admin/' . ucfirst(basename($folder)) . '/' . basename($view),
            'notFound', $gets
    );
});
$router->post('/panel/list/{folder}/{view}', function ($folder, $view) use ($routes) {
    $routes->getRoutes(
            'Admin/' . basename($folder) . '/' . basename($view),
            'notFound'
    );
});
$router->post('/panel/control/{folder}/{view}', function ($folder, $view) use ($routes) {
    $routes->getController(
            basename($folder) . '/' . basename($view),
            'notFound'
    );
});
*/