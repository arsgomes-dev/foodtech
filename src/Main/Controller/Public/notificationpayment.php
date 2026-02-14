<?php

namespace Microfw\Src\Main\Controller\Public;

use Microfw\Src\Main\Controller\Public\View\View;

class notificationPayment {

    public function index() {
        View::render('notificationPayment');
    }
}

// executa automaticamente
(new notificationPayment)->index();
