<?php

namespace Microfw\Src\Routing\Router;

class Request {

    public function uri(): string {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // remove barra final
        $uri = rtrim($uri, '/');

        // padroniza para lowercase
        return strtolower($uri ?: '/');
    }

    public function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
}
