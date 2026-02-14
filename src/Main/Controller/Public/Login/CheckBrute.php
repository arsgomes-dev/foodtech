<?php

  namespace Microfw\Src\Main\Controller\Public\Login;

  use Microfw\Src\Main\Common\Entity\Public\ClientLoginAttempts;

  /**
   * Description of CheckBrute
   *
   * @author ARGomes
   */
  class CheckBrute {

      public static function checkbrute($client) {
          $now = time();
          $valid_attempts = $now - (2 * 60 * 60);
          $attempts = new ClientLoginAttempts();
          $attempts->setClient_Id($client);
          $attempts->setTime($valid_attempts);
          $login_attempts = $attempts->getQuery();
          if (count($login_attempts) >= 5) {
              return true;
          } else {
              return false;
          }
      }
  }
  