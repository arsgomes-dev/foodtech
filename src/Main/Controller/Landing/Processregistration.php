<?php
namespace Microfw\Src\Main\Controller\Landing;

use Microfw\Src\Main\Controller\Landing\View\View;

class Processregistration {

    public function page() {
        View::render('security/processregistration');
    }
}

// executa automaticamente
(new Processregistration)->page();
