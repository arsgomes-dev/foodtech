<?php

require dirname(__DIR__) . '/bootstrap.php';

use Microfw\Src\Routing\Router\Request;

$request = new Request();

$router->resolve($request);