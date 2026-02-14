<?php
namespace Microfw\Src\Main\Controller\Api;

use Microfw\Src\Main\Controller\Api\View\View;

class Checkemail {

    public function page() {
        View::render('Search/checkemail');
    }
}

// executa automaticamente
(new Checkemail)->page();
