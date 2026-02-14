<?php

namespace Microfw\Src\Main\Controller\Admin\Login;

use Microfw\Src\Main\Controller\Admin\Login\Login;
use Microfw\Src\Main\Controller\Admin\Login\LoginCheck;
use Microfw\Src\Main\Controller\Admin\Login\ExpelsVisitor;
use Microfw\Src\Main\Controller\Admin\Login\SecSessionStart;

/**
 * Description of ProtectedPage
 *
 * @author ARGomes
 */
date_default_timezone_set('America/Bahia');

class ProtectedPage {

    public static function protectedPage() {
        SecSessionStart::secSessionSart();
        if (!isset($_SESSION['user_id'], $_SESSION['user_username'], $_SESSION['user_login_string'], $_SESSION['user_type'])) {
            ExpelsVisitor::expelsVisitor();
        } else if (LoginCheck::login_check() == false) {
            ExpelsVisitor::expelsVisitor();
        }
    }
}
