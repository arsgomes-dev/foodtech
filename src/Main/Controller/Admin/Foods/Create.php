<?php

namespace Microfw\Src\Main\Controller\Admin\Foods;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class Create {

    public function page() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/Foods/create.php';
    }
}

// execução automática
(new Create)->page();
