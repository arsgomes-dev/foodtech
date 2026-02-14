<?php
namespace Microfw\Src\Main\Controller\Admin\Tickets;

use Microfw\Src\Main\Controller\Admin\View\View;

class Departments {

    public function page() {
        View::render('Tickets/departments');
    }
}

// executa automaticamente
(new Departments)->page();
