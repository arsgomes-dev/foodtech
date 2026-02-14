<?php

use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\ClientLoginAttempts;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\Google\GoogleConfig;

$config = new McClientConfig();
if (isset($_GET['code'])) {
    $url = $config->getDomain() . "/" . $config->getUrlPublic() . "/";
    $urlPage = $config->getPageHomeClient();
    // 1. Troca o código de autorização pelo token de acesso
    $client = new GoogleConfig();
    $client = $client->config();
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    // Verifica se não houve erro
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        // 2. Obtém os dados do usuário (Google Oauth2 Service)
        $google_oauth = new \Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        if ($google_account_info) {
            // Dados recebidos
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $google_id = $google_account_info->id;
            $picture = $google_account_info->picture;

            $clientSearch = new Client;
            $clientSearch = $clientSearch->getQuery(single: true,
                    customWhere: [['column' => 'email', 'value' => $email]]);

            if ($client) {
                if ($clientSearch->getId() > 0) {
                    $clientUp = new Client;
                    $clientUp->setId($clientSearch->getId());
                    $clientUp->setGoogle_id($google_id);
                    if ($picture) {
                        $clientUp->setGoogle_photo($picture);
                    }
                    $return = $clientUp->setSaveQuery();
                    if ($return == 1) {
                        $client_id = $clientSearch->getId();
                        $username = $clientSearch->getName();
                        if ($clientSearch->getGoogle_photo() && $clientSearch->getGoogle_photo() !== "") {
                            $photo_google = $clientSearch->getGoogle_photo();
                        }
                        $photo = $clientSearch->getPhoto();

                        $client_gcid = $clientSearch->getGcid();
                        $db_password = $clientSearch->getPasswd();
                        $salt = $clientSearch->getSalt();
                        $status = $clientSearch->getStatus();
                        $premium = $clientSearch->getIs_premium();
                        $token_ai = $clientSearch->getToken_ai();
                        $lg = new Language;
                        $lg = $lg->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $clientSearch->getLanguage_id()]]);
                        $lang = $lg->getCode();
                        $lang_locale = $lg->getLocale();
                        $language = $clientSearch->getLanguage_id();

                        // Define a sessão de sucesso (exemplo simples)
                        $client_browser = $_SERVER['HTTP_USER_AGENT'];
                        $client_id = preg_replace("/[^0-9]+/", "", $client_id);
                        $_SESSION['client_id'] = $client_id;
                        $_SESSION['client_gcid'] = $client_gcid;
                        $_SESSION['client_google_id'] = $google_id;
                        $_SESSION['client_username'] = $username;
                        $_SESSION['client_photo'] = $photo;
                        if ($picture) {
                            $_SESSION['client_google_photo'] = $photo_google;
                        } else {
                            $_SESSION['client_google_photo'] = "";
                        }
                        $_SESSION['client_language'] = $language;
                        $_SESSION['client_lang'] = $lang;
                        $_SESSION['client_lang_locale'] = $lang_locale;
                        $_SESSION['client_login_string'] = hash('sha512', $password . $client_browser);
                        $_SESSION['client_premium'] = $premium;
                        $_SESSION['client_token_ai'] = $token_ai;
                        $_SESSION['client_plan'] = "";
                        $_SESSION['client_plan_code'] = "";
                        $_SESSION['client_plan_tokens'] = "";
                        $_SESSION['client_plan_message'] = "";
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

                        // Redireciona para o Dashboard
                        header('Location:' . $url . $urlPage);
                        exit;
                    } else if ($return == 3) {
                        // Se falhar, volta para o login
                        header('Location:' . $url);
                        exit;
                    }
                }
            }
        }
    }
}
?>