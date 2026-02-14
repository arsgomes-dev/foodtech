<?php
namespace Microfw\Src\Main\Common\Settings\Public\Google;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Google\Client as Google_Client; // <-- ESSENCIAL

class GoogleConfig {

    public function config(): Google_Client {
        $config = new McClientConfig();

        // Configurações do Google
        $clientID = env('GOOGLE_OAUTH_CLIENT_ID');
        $clientSecret = env('GOOGLE_OAUTH_CLIENT_SECRET'); // Ajuste variável de ambiente
        $redirectUri = $config->getDomain() . "/" . $config->getUrlPublic() . '/oauth2Logincallback';

        // Instância do Cliente Google
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        return $client;
    }
}
