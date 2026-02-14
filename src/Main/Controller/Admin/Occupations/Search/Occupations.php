<?php

namespace Microfw\Src\Main\Controller\Admin\Occupations\Search;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class Select_occupations {

    public function page() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/Occupations/selectOccupations.php';
    }
}

// execução automática
(new Select_occupations)->page();
