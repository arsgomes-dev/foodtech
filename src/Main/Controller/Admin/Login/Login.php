<?php

namespace Microfw\Src\Main\Controller\Admin\Login;

use Microfw\Src\Main\Controller\Admin\Login\CheckBrute;
use Microfw\Src\Main\Controller\Admin\Login\EmailUnlock;
use Microfw\Src\Main\Controller\Admin\Privileges\Search\Privileges;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Currency;
use Microfw\Src\Main\Common\Entity\Admin\LoginAttempts;

/**
 * Description of Login
 *
 * @author ARGomes
 */
if (!isset($_SESSION)) {
    session_start();
}

class Login {

    public static function login($email, $password2, $language) {
        $username = "";
        $db_password = "";
        $salt = "";
        $status = "";
        $user_id = "";
        $lang = "";
        $lang_locale = "";
        $currency_locale = "";
        $currency = "";
        $currency_placeholder = "";
        $currency_id = "";
        $user = new User;
        $user = $user->getQuery(single: true, customWhere: [['column' => 'email', 'value' => $email]]);
        if ($user) {
            if ($user !== null) {
                $user_id = $user->getId();
                $username = $user->getName();
                $photo = $user->getPhoto();
                $user_gcid = $user->getGcid();
                $db_password = $user->getPasswd();
                $salt = $user->getSalt();
                $privilege = $user->getPrivilege_id();
                $privileges = new Privileges();
                $privilege_type = $privileges->search($privilege);
                $administrativo = $user->getAdministrative();
                $status = $user->getStatus();
                $lg = new Language;
                $lg = $lg->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $user->getLanguage_id()]]);
                $lang = $lg->getCode();
                $lang_locale = $lg->getLocale();
                $language = $user->getLanguage_id();
                if ($user->getCurrency_id() !== null && $user->getCurrency_id() !== "" && $user->getCurrency_id() > 0) {
                    $currencySearch = new Currency;
                    $currencySearch = $currencySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $user->getCurrency_id()]]);
                    $currency = $currencySearch->getCurrency();
                    $currency_locale = $currencySearch->getLocale();
                    $currency_placeholder = $currencySearch->getPlaceholder();
                    $currency_id = $user->getCurrency_id();
                } else {
                    $currencySearch = new Currency;
                    $currencySearch = $currencySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $lg->getCurrency_id()]]);
                    $currency = $currencySearch->getCurrency();
                    $currency_locale = $currencySearch->getLocale();
                    $currency_placeholder = $currencySearch->getPlaceholder();
                    $currency_id = $lg->getCurrency_id();
                }
            }
            //editar aqui           
            $password = hash('sha512', $password2 . $salt);
            if ($status === 1) {
                if (CheckBrute::checkbrute($user_id) === true) {
                    EmailUnlock::email_unlock($email, $username);
                    $userSave = new User();
                    $userSave->setId($user_id);
                    $userSave->setStatus(2);
                    $userSave->setSaveQuery();
                    return 3;
                    exit();
                } else {
                    if ($db_password == $password) {
                        // login correto
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_gcid'] = $user_gcid;
                        $_SESSION['user_username'] = $username;
                        $_SESSION['user_photo'] = $photo;
                        $_SESSION['user_type'] = $privilege_type;
                        $_SESSION['user_ad'] = $administrativo;
                        $_SESSION['user_language'] = $language;
                        $_SESSION['user_currency'] = $currency;
                        $_SESSION['user_currency_locale'] = $currency_locale;
                        $_SESSION['user_currency_placeholder'] = $currency_placeholder;
                        $_SESSION['user_currency_id'] = $currency_id;
                        $_SESSION['user_lang'] = $lang;
                        $_SESSION['user_lang_locale'] = $lang_locale;
                        $_SESSION['user_login_string'] = hash('sha512', $password . $user_browser);
                        $userSave = new User();
                        $userSave->setId($user_id);
                        $userSave->setSession_date($userSave->getDateTime());
                        $userSave->setSession_date_last($userSave->getDateTime());
                        $userSave->setSaveQuery();
                        //exclui informações de tentativas incorretas anteriores
                        $attempts = new LoginAttempts();
                        $attempts->setTable_db_primaryKey("user_id");
                        $attempts->setUser_Id($user_id);
                        $attempts->setDeleteQuery();
                        return 1;
                    } else {
                        //salva no DB tentativas incorretas de login
                        $now = time();
                        $attempts = new LoginAttempts();
                        $attempts->setUser_Id($user_id);
                        $attempts->setTime($now);
                        $attempts->setSaveQuery();
                        $atts = new LoginAttempts();
                        $attempts->setTable_db_primaryKey("user_id");
                        $valid_attempts = $now - (2 * 60 * 60);
                        $count = $atts->getCountSumQuery(
                                customWhere: [['column' => 'user_id', 'value' => $user_id]]
                        );
                        if ($count['total_count'] >= 5) {
                            EmailUnlock::email_unlock($email, $username);
                            $userSave = new User();
                            $userSave->setId($user_id);
                            $userSave->setStatus(2);
                            $userSave->setSaveQuery();
                            return 3;
                        } else {
                            return 2;
                        }
                    }
                }
            } else if ($status === 0) {
                return 5;
            } else if ($status === 2) {
                EmailUnlock::email_unlock($email, $username);
                return 3;
            }
            //ate aqui
        } else {
            return 2;
        }
    }
}
