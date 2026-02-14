<?php

namespace Microfw\Src\Main\Controller\Admin\Signatures\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Payment {

    public function page($id = null) {
        // passar o id para a view
        View::render('Signatures/payment');
    }
}
// executa automaticamente
(new Payment)->page();
