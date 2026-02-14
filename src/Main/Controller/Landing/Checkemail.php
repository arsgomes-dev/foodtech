<?php
namespace Microfw\Src\Main\Controller\Landing;

use Microfw\Src\Main\Controller\Landing\View\View;

class Checkemail {

    public function page() {
        View::render('search/checkemail');
    }
}

// executa automaticamente
(new Checkemail)->page();
