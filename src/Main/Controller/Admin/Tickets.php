<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Tickets {

    public function page() {
        View::render('tickets');
    }
}

// executa automaticamente
(new Tickets)->page();
