<?php

namespace Microfw\Src\Main\Controller\Admin\Departments\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Department {

    public function page($id = null) {
        // passar o id para a view
        View::render('Departments/department', [
            'gets' => ['code' => $id]
        ]);
    }
}
