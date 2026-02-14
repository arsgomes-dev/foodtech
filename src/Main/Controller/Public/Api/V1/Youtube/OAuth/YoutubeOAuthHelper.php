<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Youtube\OAuth;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\YoutubeChannelToken;
use Microfw\Src\Main\Common\Entity\Public\YoutubeTokens;

class YoutubeOAuthHelper {

    function getClientAccessToken($customer_id) {
        $channelToken = new YoutubeTokens();
        $channelTokens = new YoutubeTokens();
        //$channelTokens->setPrincipal("1");
        $channelTokens->setCustomer_id($customer_id);
        $channelToken = $channelTokens->getAll(1);
        if (count($channelToken) > 0) {
            $channelToken = $channelToken[0];
            $created = $channelToken->getCreate_token() ?? 0;
            $expiresIn = $channelToken->getExpires_in() ?? 3600;

            if (time() > $created + $expiresIn) {
                $refreshToken = $channelToken->getRefresh_token() ?? null;
                if (!$refreshToken) {
                    return null;
                } else {
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

                    if (!isset($newData['access_token'])) {
                        return null;
                    } else {
                        $token = new YoutubeTokens;
                        $token->setId($channelToken->getId());
                        //$token->setChannel_id($channelToken->getChannel_id());
                        //$token->setCustomer_id($customer_id);
                        $token->setCreate_token(time());
                        $token->setAccess_token($newData['access_token']);
                        $token->setExpires_in(3600);
                        $return = $token->setSave();
                        if ($return === 1 || $return === 2) {
                            return $newData['access_token'];
                        } else {
                            return null;
                        }
                    }
                }
            }
            return $channelToken->getAccess_token() ?? null;
        } else {
            return null;
        }
    }

    function getChannel($access_token) {
        $translate = new Translate();
        $channelToken = new YoutubeChannelToken;
        // Descobrir o canal do usuário autenticado
        $ch = curl_init("https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics,brandingSettings,contentDetails,status&mine=true");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$access_token}"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseChannel = curl_exec($ch);
        curl_close($ch);

        $channelData = json_decode($responseChannel, true);
        if (!is_array($channelData) || !isset($channelData['items']) || empty($channelData['items'])) {
            $channelToken->setCode(2);
            $channelToken->setMessage($translate->translate("Não foi possível identificar o canal dessa conta do google!", $_SESSION['client_lang']));
            return $channelToken;
        } else {
            $channel = $channelData['items'][0];
            if (isset($channel['snippet']['thumbnails'])) {
                $thumbs = $channel['snippet']['thumbnails'];

                $thumb = $thumbs['high']['url'] ?? $thumbs['medium']['url'] ?? $thumbs['default']['url'] ?? null;
            }
            $publishedAtRaw = $channel['snippet']['publishedAt'] ?? null;
            $publishedAt = null;

            if (!empty($publishedAtRaw)) {
                $dt = new \DateTime($publishedAtRaw);
                $publishedAt = $dt->format('Y-m-d H:i:s'); // formato que o MySQL aceita
            }
            $channelToken->setId($channel['id'] ?? 0);
            $channelToken->setTitle($channel['snippet']['title'] ?? '');
            $channelToken->setDescription($channel['snippet']['description'] ?? '');
            $channelToken->setPublishedAt($publishedAt ?? '');
            $channelToken->setThumbnail($thumb);
            $channelToken->setViews(isset($channel['statistics']['viewCount']) ? (int) $channel['statistics']['viewCount'] : 0);
            $channelToken->setSubscribers(isset($channel['statistics']['subscriberCount']) ? (int) $channel['statistics']['subscriberCount'] : 0);
            $channelToken->setVideos(isset($channel['statistics']['videoCount']) ? (int) $channel['statistics']['videoCount'] : 0);
            $channelToken->setCode(1);
            if ($channelToken->getId() === 0 || $channelToken->getId() === null || $channelToken->getId() === "") {
                $channelToken->setCode(2);
                $channelToken->setMessage($translate->translate("Não foi possível identificar o canal dessa conta do google!", $_SESSION['client_lang']));
                return $channelToken;
            } else {
                return $channelToken;
            }
        }
    }
}
