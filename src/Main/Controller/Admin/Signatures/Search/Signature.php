<?php

namespace Microfw\Src\Main\Controller\Admin\Signatures\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Signature {

    public function page($id = null) {
        // passar o id para a view
        View::render('Signatures/signature', [
            'gets' => ['code' => $id]
        ]);
    }
}
