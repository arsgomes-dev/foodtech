<?php

namespace Microfw\Src\Main\Controller\Admin\Tickets\Departments;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class Pagination_department {

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/Tickets/paginationDepartment.php';
    }
}

// execução automática
(new Pagination_department)->index();
