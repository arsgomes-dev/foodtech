<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Home {

    public function index() {
        View::render('home', [
            'title' => 'Dashboard'
        ]);
    }
}

// executa automaticamente
(new Home)->index();
