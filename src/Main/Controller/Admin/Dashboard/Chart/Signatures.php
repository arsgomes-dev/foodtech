<?php
namespace Microfw\Src\Main\Controller\Admin\Dashboard\Chart;

use Microfw\Src\Main\Controller\Admin\View\View;

class Signatures {

    public function page() {
        View::render('Dashboard/signatureChart');
    }
}

// executa automaticamente
(new Signatures)->page();
