<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Huggingface;

class HuggingfaceExCurl {

    public static function exCurl($url, $payload, $hfToken, $extraHeaders = []) {

        $ch = curl_init($url);

        // ------------------------------------
        // HEADERS
        // ------------------------------------
        $headers = array_merge([
            "Authorization: Bearer " . $hfToken,
            "Content-Type: application/json",
            "Accept" => "image/png, image/jpeg"
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Já está JSON string
        curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // EXECUTA
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $curlError = curl_error($ch);

        curl_close($ch);

        return [
            "response" => $response,
            "httpcode" => $httpcode,
            "contentType" => $contentType,
            "curlError" => $curlError,
            "err" => $curlError ? "Erro cURL HuggingFace: " . $curlError : ""
        ];
    }
}
