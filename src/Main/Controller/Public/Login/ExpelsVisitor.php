<?php

namespace Microfw\Src\Main\Controller\Public\Login;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

/**
 * Description of ExpelsVisitor
 *
 * @author ARGomes
 */
class ExpelsVisitor {

    public static function expelsVisitor() {
        $config = new McClientConfig;
        unset($_SESSION['client_id'], $_SESSION['client_username'], $_SESSION['client_login_string'], $_SESSION['client_type']);
        RedirectUrl::redirectUrl();
        exit();
    }
}
