<?php

  namespace Microfw\Src\Main\Controller\Landing\Login;

  use Microfw\Src\Main\Common\Entity\Public\Client;

  /**
   * Description of LoginCheck
   *
   * @author ARGomes
   */
  class LoginCheck {

      static function login_check() {
          if (isset($_SESSION['client_id'], $_SESSION['client_username'], $_SESSION['client_login_string'])) {
              $client_id = (int) $_SESSION['client_id'];
              $login_string = $_SESSION['client_login_string'];
              $client_browser = $_SERVER['HTTP_USER_AGENT'];
              $usr = new Client;
              $us = $usr->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $client_id]]);
              if ($us) {
                  if ($us->getId() === $client_id) {
                      $db_password = $us->getPasswd();
                      $login_check = hash('sha512', $db_password . $client_browser);
                      if ($login_check === $login_string) {
                          // Usuário logado!!!! 
                          date_default_timezone_set('America/Bahia');
                          $Data2 = date('Y-m-d ');
                          $hora0 = date('H:i:s', time());
                          $dataI = $Data2 . $hora0;
                          $clientSession = new Client();
                          $clientSession->setId($client_id);
                          $clientSession->setSession_date($dataI);
                          $clientSession->setSession_date_last($dataI);
                          $clientSession->setSaveQuery($clientSession);
                          return 1;
                      } else {
                          return 2;
                      }
                  } else {
                      return 2;
                  }
              } else {
                  return 6;
              }
          } else {
              return 2;
          }
      }
  }
  