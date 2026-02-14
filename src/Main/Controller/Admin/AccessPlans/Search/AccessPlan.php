<?php

namespace Microfw\Src\Main\Controller\Admin\Accessplans\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class AccessPlan {

    public function page($id = null) {
        // passar o id para a view
        View::render('AccessPlans/plan', [
            'gets' => ['code' => $id]
        ]);
    }
}
