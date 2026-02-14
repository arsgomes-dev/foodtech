<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Departments {

    public function page() {
        View::render('departments');
    }
}

// executa automaticamente
(new Departments)->page();
