<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Customers {

    public function page() {
        View::render('customers');
    }
}

// executa automaticamente
(new Customers)->page();
