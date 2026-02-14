<?php

namespace Microfw\Src\Main\Controller\Admin\Customers\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Customer {

    public function page($id = null) {
        // passar o id para a view
        View::render('Customers/customer', [
            'gets' => ['code' => $id]
        ]);
    }
}
