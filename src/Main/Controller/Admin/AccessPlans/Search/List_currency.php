<?php

namespace Microfw\Src\Main\Controller\Admin\Accessplan\Search;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class List_currency {

    public function page() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/AccessPlans/listCurrency.php';
    }
}

// execução automática
(new List_currency)->page();
