<?php

  namespace Microfw\Src\Main\Controller\Admin\Login;

  use Microfw\Src\Main\Common\Entity\Admin\McConfig;
  use Microfw\Src\Main\Controller\Admin\Login\SecSessionStart;

  /**
   * Description of CloseLogin
   *
   * @author ARGomes
   */
  class CloseLogin {

      public static function closeLogin() {
          $config = new McConfig;
          SecSessionStart::secSessionSart();
          $_SESSION = array();
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
          session_destroy();
          header("Location:".$config->getDomainAdmin()."/".$config->getUrlAdmin() . "/login");
          exit();
      }
  }
  