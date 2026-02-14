<?php

namespace Microfw\Src\Main\Controller\Admin\View;

class View {

    public static function render(string $view, array $data = []) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Admin/' . $view . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            echo "View não encontrada: {$view}";
            exit;
        }

        extract($data); // transforma array em variáveis
        require $path;
    }
}
