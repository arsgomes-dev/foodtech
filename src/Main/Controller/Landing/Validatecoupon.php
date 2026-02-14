<?php
namespace Microfw\Src\Main\Controller\Landing;

use Microfw\Src\Main\Controller\Landing\View\View;

class Validatecoupon {

    public function page() {
        View::render('security/validatecoupon');
    }
}

// executa automaticamente
(new Validatecoupon)->page();
