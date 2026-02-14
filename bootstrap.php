<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use \Dotenv\Dotenv;
use Microfw\Src\Routing\Router\Router;
use Microfw\Src\Routing\Router\Request;

$router = new Router();

// Carregar .env
$dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

// Função helper env()
function env(string $key, $default = null) {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? $default;

    if (is_string($value)) {
        $lower = strtolower($value);
        if ($lower === 'true')
            return true;
        if ($lower === 'false')
            return false;
        if ($lower === 'null')
            return null;
    }

    return $value;
}

// Cache de rotas
$cacheFile = $_SERVER['DOCUMENT_ROOT'] . '/storage/cache/routes.cache.php';
if (file_exists($cacheFile) && (env('APP_ENV') ?? 'production') === 'production') {
    require $cacheFile;
} else {
    ob_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/src/Routing/Routes/routes.php';
    $routesCode = ob_get_clean();

    if ((env('APP_ENV') ?? 'production') === 'production') {
        file_put_contents($cacheFile, "<?php\n\n$routesCode");
    }
}
