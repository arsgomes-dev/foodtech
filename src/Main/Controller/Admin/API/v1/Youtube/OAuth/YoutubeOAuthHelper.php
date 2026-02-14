<?php

namespace Microfw\Src\Main\Controller\Admin\Api\V1\Youtube;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class YoutubeOAuthHelper {

    function getUserAccessToken($userId) {
        $config = new McConfig();
        $tokenFile = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/youtube/cache/tokens_user_$userId.json";
        if (!file_exists($tokenFile))
            return null;

        $data = json_decode(file_get_contents($tokenFile), true);
        $created = $data['created'] ?? 0;
        $expiresIn = $data['expires_in'] ?? 3600;

        if (time() > $created + $expiresIn) {
            $refreshToken = $data['refresh_token'] ?? null;
            if (!$refreshToken)
                return null;

            $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query([
                        'client_id' => env('GOOGLE_YOUTUBE_CLIENT_ID'),
                        'client_secret' => env('GOOGLE_YOUTUBE_CLIENT_SECRET'),
                        'refresh_token' => $refreshToken,
                        'grant_type' => 'refresh_token'
                    ])
                ]
            ]));

            $newData = json_decode($response, true);
            if (!isset($newData['access_token']))
                return null;

            $newData['refresh_token'] = $refreshToken;
            $newData['created'] = time();
            file_put_contents($tokenFile, json_encode($newData));

            return $newData['access_token'];
        }

        return $data['access_token'] ?? null;
    }
}
