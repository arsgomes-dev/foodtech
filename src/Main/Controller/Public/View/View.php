<?php

namespace Microfw\Src\Main\Controller\Public\View;

class View
{
    public static function render(string $view, array $data = [])
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Public/' . $view . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            echo "View não encontrada: {$view}";
            exit;
        }

        extract($data); // transforma array em variáveis
        require $path;
    }
}
