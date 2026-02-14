<?php
namespace Microfw\Src\Main\Controller\Admin;

use Microfw\Src\Main\Controller\Admin\View\View;

class Users {

    public function page() {
        View::render('users');
    }
}

// executa automaticamente
(new Users)->page();
