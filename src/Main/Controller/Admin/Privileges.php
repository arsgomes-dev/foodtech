<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Privileges {

    public function page() {
        View::render('privileges');
    }
}

// executa automaticamente
(new Privileges)->page();
