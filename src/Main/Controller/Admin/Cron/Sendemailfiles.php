<?php

namespace Microfw\Src\Main\Controller\Admin\Cron;

use Microfw\Src\Main\Controller\Admin\View\View;

class Sendemailfiles {

    public function page($id = null) {
        // passar o id para a view
        View::render('Cron/sendEmailFiles', [
            'gets' => ['code' => $id]
        ]);
    }
}
// executa automaticamente
(new Sendemailfiles)->page();