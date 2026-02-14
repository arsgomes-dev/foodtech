<?php

namespace Microfw\Src\Main\Controller\Public;

use Microfw\Src\Main\Controller\Public\View\View;

class Subscribe {

    public function page() {
        View::render('subscribe');
    }
}

// executa automaticamente
(new Subscribe)->page();
