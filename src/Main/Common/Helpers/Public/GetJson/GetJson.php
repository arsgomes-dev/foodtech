<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Helpers\Public\GetJson;

use stdClass;

/**
 * Description of GetJson
 *
 * @author Ricardo Gomes
 */
class GetJson {

    public function getJson(string $url): ?object {
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            error_log("Erro CURL: $error");
            return null;
        }

        // remove possível callback JSONP
        $response = preg_replace('/^[^(]+\((.*)\);?$/', '$1', trim($response));

        // ⚙️ converte aspas simples para duplas, se for JSON inválido
        if (strpos($response, "'") !== false && strpos($response, '"') === false) {
            $response = preg_replace("/'([^']*)':/", '"$1":', $response); // chaves
            $response = preg_replace("/:'([^']*)'/", ':"$1"', $response); // valores
        }

        $data = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Erro ao decodificar JSON: ' . json_last_error_msg());
            return null;
        }

        return $data;
    }
}
