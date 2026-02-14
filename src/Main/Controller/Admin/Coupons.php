<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Coupons {

    public function page() {
        View::render('coupons');
    }
}

// executa automaticamente
(new Coupons)->page();
