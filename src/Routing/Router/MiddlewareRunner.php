<?php

namespace Microfw\Src\Routing\Router;

class MiddlewareRunner
{
    public static function run(string $viewPath): void
    {
        $path = dirname($viewPath);

        while ($path !== '/' && $path !== '.') {
            $middleware = $path . '/.middleware.php';

            if (file_exists($middleware)) {
                $fn = require $middleware;
                if (is_callable($fn)) {
                    $fn();
                }
            }

            $path = dirname($path);
        }
    }
}
