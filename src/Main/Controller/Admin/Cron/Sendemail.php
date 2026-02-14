<?php

namespace Microfw\Src\Main\Controller\Admin\Cron;

use Microfw\Src\Main\Controller\Admin\View\View;

class Sendemail {

    public function page($id = null) {
        // passar o id para a view
        View::render('Cron/sendEmail', [
            'gets' => ['code' => $id]
        ]);
    }
}
// executa automaticamente
(new Sendemail)->page();