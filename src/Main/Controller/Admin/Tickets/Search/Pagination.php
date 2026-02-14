<?php

namespace Microfw\Src\Main\Controller\Admin\Tickets\Search;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class Pagination {

    public function page() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/Tickets/pagination.php';
    }
}

// execução automática
(new Pagination)->page();
