<?php

namespace Microfw\Src\Main\Controller\Public\Login;

use Microfw\Src\Main\Controller\Public\Login\CheckBrute;
use Microfw\Src\Main\Controller\Public\Login\EmailUnlock;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\ClientLoginAttempts;
use \Microfw\Src\Main\Common\Entity\Public\YoutubeChannels;

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
        $client_id = "";
        $lang = "";
        $lang_locale = "";
        $channel_gcid = "";
        $channel_title = "";
        $channel_thumb = "";
        $clients = new Client;
        $client = new Client;
        $clients->setEmail($email);
        $clients = $clients->getQuery();
        if (count($clients) > 0) {
            for ($i = 0; $i < count($clients); $i++) {
                if ($clients[$i]) {
                    $client = $clients[$i];
                    $client_id = $client->getId();
                    $username = $client->getName();
                    $photo = $client->getPhoto();
                    $client_gcid = $client->getGcid();
                    $db_password = $client->getPasswd();
                    $salt = $client->getSalt();
                    $status = $client->getStatus();
                    $premium = $client->getIs_premium();
                    $token_ai = $client->getToken_ai();
                    $lg = new Language;
                    $lg = $lg->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $client->getLanguage_id()]]);
                    $lang = $lg->getCode();
                    $lang_locale = $lg->getLocale();
                    $language = $client->getLanguage_id();

                    $channel = new YoutubeChannels;
                    $channel = $channel->getQuery(single: true, customWhere: [['column' => 'customer_id', 'value' => $client->getGcid()],['column' => 'workspace', 'value' => 1]]);
                    if($channel !== null){
                    $channel_gcid = $channel->getGcid();
                    $channel_title = $channel->getTitle();
                    $channel_thumb = $channel->getThumbnail();
                    }
                }
            }
            //editar aqui           
            $password = hash('sha512', $password2 . $salt);
            if ($status === 1) {
                if (CheckBrute::checkbrute($client_id) === true) {
                    EmailUnlock::email_unlock($email, $username);
                    $clientSave = new Client();
                    $clientSave->setId($client_id);
                    $clientSave->setStatus(2);
                    $clientSave->setSaveQuery();
                    return 3;
                    exit();
                } else {
                    if ($db_password == $password) {
                        // login correto
                        $client_browser = $_SERVER['HTTP_USER_AGENT'];
                        $client_id = preg_replace("/[^0-9]+/", "", $client_id);
                        $_SESSION['client_id'] = $client_id;
                        $_SESSION['client_gcid'] = $client_gcid;
                        $_SESSION['client_username'] = $username;
                        $_SESSION['client_photo'] = $photo;
                        $_SESSION['client_language'] = $language;
                        $_SESSION['client_lang'] = $lang;
                        $_SESSION['client_lang_locale'] = $lang_locale;
                        $_SESSION['client_login_string'] = hash('sha512', $password . $client_browser);
                        $_SESSION['client_premium'] = $premium;
                        $_SESSION['client_token_ai'] = $token_ai;
                        $_SESSION['client_plan'] = "";
                        $_SESSION['client_plan_code'] = "";
                        $_SESSION['client_plan_title'] = "";
                        $_SESSION['client_plan_tokens'] = "";
                        $_SESSION['client_plan_scripts'] = "";
                        $_SESSION['client_plan_channels'] = "";
                        $_SESSION['client_plan_message'] = "";
                        $_SESSION['client_plan_tokens_usage'] = "";
                        $_SESSION['active_workspace_gcid'] = $channel_gcid ?? null;
                        $_SESSION['active_workspace_thumb'] = $channel_thumb ?? null;
                        $_SESSION['active_workspace_title'] = $channel_title ?? "Nenhuma selecionada";
                        
                        $clientSave = new Client();
                        $clientSave->setId($client_id);
                        $clientSave->setSession_date($clientSave->getDateTime());
                        $clientSave->setSession_date_last($clientSave->getDateTime());
                        $clientSave->setSaveQuery();
                        //exclui informações de tentativas incorretas anteriores
                        $attempts = new ClientLoginAttempts();
                        $attempts->setTable_db_primaryKey("client_id");
                        $attempts->setClient_Id($client_id);
                        $attempts->setDeleteQuery();
                        return 1;
                    } else {
                        //salva no DB tentativas incorretas de login
                        $now = time();
                        $attempts = new ClientLoginAttempts();
                        $attempts->setClient_Id($client_id);
                        $attempts->setTime($now);
                        $attempts->setSaveQuery();
                        $atts = new ClientLoginAttempts();
                        $attempts->setTable_db_primaryKey("client_id");
                        $atts->setClient_Id($client_id);
                        $valid_attempts = $now - (2 * 60 * 60);
                        $login_attempts = $atts->getQuery();
                        if (count($login_attempts) >= 5) {
                            EmailUnlock::email_unlock($email, $username);
                            $clientSave = new Client();
                            $clientSave->setId($client_id);
                            $clientSave->setStatus(2);
                            $clientSave->setSaveQuery();
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
