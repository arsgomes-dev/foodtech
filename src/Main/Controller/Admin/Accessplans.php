<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Accessplans {

    public function page() {
        View::render('accessPlans');
    }
}

// executa automaticamente
(new Accessplans)->page();
