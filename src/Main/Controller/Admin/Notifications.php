<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Notifications {

    public function page() {
        View::render('notifications');
    }
}

// executa automaticamente
(new Notifications)->page();
