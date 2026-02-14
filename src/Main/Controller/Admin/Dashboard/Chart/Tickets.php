<?php
namespace Microfw\Src\Main\Controller\Admin\Dashboard\Chart;

use Microfw\Src\Main\Controller\Admin\View\View;

class Tickets {

    public function page() {
        View::render('Dashboard/ticketsChart');
    }
}

// executa automaticamente
(new Tickets)->page();
