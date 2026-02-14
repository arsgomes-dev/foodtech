<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Gemini;

class GeminiExCurl {

    public static function exCurl($url, $data, $apiKey) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "x-goog-api-key: " . $apiKey,
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        return [
            "response" => $response,
            "httpcode" => $httpcode,
            "err" => $err
        ];
    }
}
