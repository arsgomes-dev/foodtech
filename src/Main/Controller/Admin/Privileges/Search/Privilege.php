<?php

namespace Microfw\Src\Main\Controller\Admin\Privileges\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Privilege {

    public function page($id = null) {
        // passar o id para a view
        View::render('Privileges/privilege', [
            'gets' => ['code' => $id]
        ]);
    }
}
