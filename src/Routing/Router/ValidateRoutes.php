<?php

namespace Microfw\Src\Routing\Router;

class ValidateRoutes {

    protected function render(string $file, $gets = null) {
        ob_start();
        require $file;
        echo ob_get_clean();
    }

    protected function method(): string {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
    }

    /* =========================
     * VIEW (PUBLIC / PANEL)
     * ========================= */

    public function view(string $dir, string $notFound, $gets = null) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/{$dir}.php";

        if (!file_exists($file)) {
            $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/{$notFound}.php";
        }

        $this->render($file, $gets);
    }

    /* =========================
     * CONTROLLER (POST / ACTION)
     * ========================= */

    public function controller(string $dir, string $notFound, $gets = null) {

        $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/Controller/{$dir}.php";

        if (!file_exists($file)) {
            $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/{$notFound}.php";
        }

        $this->render($file, $gets);
    }

    /* =========================
     * HTTP / API
     * ========================= */

    public function http(string $dir, string $notFound, array $gets = null) {

        $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/Http/Controllers/{$dir}.php";

        if (!file_exists($file)) {
            $file = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/{$notFound}.php";
        }

        $this->render($file, $gets);
    }

    /* =========================
     * LEGADO (NÃO REMOVER)
     * ========================= */

    public function getRoutes($dir, $notFound, $gets = null) {
        $this->view($dir, $notFound, (array) $gets);
    }

    public function getController($dir, $notFound, $gets = null) {
        $this->controller($dir, $notFound, (array) $gets);
    }

    public function getHttp($dir, $notFound, $gets = null) {
        $this->http($dir, $notFound, (array) $gets);
    }
}
