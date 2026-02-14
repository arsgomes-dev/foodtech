<?php

namespace Microfw\Src\Main\Controller\Admin\Tickets\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Ticket {

    public function page($id = null) {
        // passar o id para a view
        View::render('Tickets/ticket', [
            'gets' => ['code' => $id]
        ]);
    }
}
