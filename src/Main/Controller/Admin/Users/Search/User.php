<?php

namespace Microfw\Src\Main\Controller\Admin\Users\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class User {

    public function page($id = null) {
        // passar o id para a view
        View::render('Users/user', [
            'gets' => ['code' => $id]
        ]);
    }
}
