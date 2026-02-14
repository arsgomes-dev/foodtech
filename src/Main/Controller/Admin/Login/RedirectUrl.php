<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Admin\Login;
use \Microfw\Src\Main\Common\Entity\Admin\McConfig;

/**
 * Description of RedirectUrl
 *
 * @author Ricardo Gomes
 */
class RedirectUrl {
    public static function redirectUrl() {
        $config = new McConfig;
        header("Location:" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() ."/". "login");
    }
}
