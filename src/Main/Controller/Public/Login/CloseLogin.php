<?php

  namespace Microfw\Src\Main\Controller\Public\Login;

  use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
  use Microfw\Src\Main\Controller\Public\Login\SecSessionStart;

  /**
   * Description of CloseLogin
   *
   * @author ARGomes
   */
  class CloseLogin {

      public static function closeLogin() {
          $config = new McClientConfig;
          SecSessionStart::secSessionSart();
          $_SESSION = array();
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
          session_destroy();
          header("Location:".$config->getDomain()."/".$config->getUrlPublic() . "/login");
          exit();
      }
  }
  