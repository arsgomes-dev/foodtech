<?php
namespace Microfw\Src\Main\Controller\Landing;

use Microfw\Src\Main\Controller\Landing\View\View;

class Signatures {

    public function page() {
        View::render('signatures');
    }
}

// executa automaticamente
(new Signatures)->page();
