<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Youtube\OAuth;

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;

class YoutubeOAuthTokenService {

    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct(string $clientId, string $clientSecret, string $redirectUri) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Solicita o token ao Google OAuth2.
     *
     * @param string $code Código retornado pelo Google.
     * @return array Retorno com: success, http_code, data, error_message
     */
    public function requestAccessToken(string $code): array {
        $translate = new Translate();
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
        ]));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($response, true);

        // === Tratamento interno ===
        if ($httpCode !== 200 || isset($json['error'])) {
            return [
                'success' => false,
                'http_code' => $httpCode,
                'data' => $json,
                'error_message' => $json['error_description'] ?? $json['error'] ??  $translate->translate("Erro desconhecido", $_SESSION['client_lang'])
            ];
        }

        return [
            'success' => true,
            'http_code' => $httpCode,
            'data' => $json,
            'error_message' => null
        ];
    }
}
