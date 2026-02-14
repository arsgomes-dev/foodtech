<?php
namespace Microfw\Src\Main\Controller\Landing;

use Microfw\Src\Main\Controller\Landing\View\View;

class Payment {

    public function page() {
        View::render('security/payment');
    }
}

// executa automaticamente
(new Payment)->page();
