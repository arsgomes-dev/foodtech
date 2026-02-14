<?php

namespace Microfw\Src\Main\Controller\Admin\Coupons\Search;

use Microfw\Src\Main\Controller\Admin\View\View;

class Coupon {

    public function page($id = null) {
        // passar o id para a view
        View::render('Coupons/coupon', [
            'gets' => ['code' => $id]
        ]);
    }
}
