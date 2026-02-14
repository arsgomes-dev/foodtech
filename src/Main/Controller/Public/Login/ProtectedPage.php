<?php

namespace Microfw\Src\Main\Controller\Public\Login;

use Microfw\Src\Main\Controller\Public\Login\LoginCheck;
use Microfw\Src\Main\Controller\Public\Login\ExpelsVisitor;
use Microfw\Src\Main\Controller\Public\Login\SecSessionStart;

/**
 * Description of ProtectedPage
 *
 * @author ARGomes
 */
date_default_timezone_set('America/Bahia');

class ProtectedPage {
    public static function protectedPage() {
        SecSessionStart::secSessionSart();
        if (!isset($_SESSION['client_id'], $_SESSION['client_username'], $_SESSION['client_login_string'])) {
            ExpelsVisitor::expelsVisitor();
            exit();
        } else if (LoginCheck::login_check() == false) {
            ExpelsVisitor::expelsVisitor();
            exit();
        }
    }
}
