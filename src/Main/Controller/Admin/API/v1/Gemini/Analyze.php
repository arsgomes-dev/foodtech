<?php

namespace Microfw\Src\Main\Controller\Admin\Api\V1\Gemini;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class Analyze {

    public function page() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $config = new McConfig;
            header('Location: ' . $config->getDomain() . "/" . $config->getUrlAdmin());
            exit;
            return;
        }

        require $_SERVER['DOCUMENT_ROOT']
                . '/src/Main/View/Admin/API/V1/Gemini/analyze.php';
    }
}

// execução automática
(new Analyze)->page();
