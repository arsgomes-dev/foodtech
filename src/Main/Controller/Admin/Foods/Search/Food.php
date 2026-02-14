<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Admin\Foods\Search;

use Microfw\Src\Main\Controller\Admin\View\View;
/**
 * Description of Food
 *
 * @author Ricardo Gomes
 */
class Food {
     public function page($id = null) {
        // passar o id para a view
        View::render('Foods/food', [
            'gets' => ['code' => $id]
        ]);
    }
}