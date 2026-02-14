<?php

namespace Microfw\Src\Main\Controller\Admin\Tickets\Departments;

use Microfw\Src\Main\Controller\Admin\View\View;

class Department {

    public function page($id = null) {
        // passar o id para a view
        View::render('Tickets/department', [
            'gets' => ['code' => $id]
        ]);
    }
}
