<?php

namespace Microfw\Src\Main\Controller\Public;

use Microfw\Src\Main\Controller\Public\View\View;

class Home {

    public function index() {
        View::render('home', [
            'title' => 'Dashboard'
        ]);
    }
}

// executa automaticamente
(new Home)->index();
