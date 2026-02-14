<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Admin\API\v1\Clients;

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;

class OAuth2 {

    function getDecrypt($encryptedKeyB64 = null, $ivB64 = null, $tagB64 = null, $ciphertextB64 = null, $privatePem = null) {
        $translate = new Translate();
        $lang = new Language;
        $lang->setActive("1");
        $lang = $lang->getAll();
        $cr = new Language;
        $cr = $lang[0];
        $lg = $cr->getId();
        $lg_code = $cr->getCode();

        if (!$encryptedKeyB64 || !$ivB64 || !$tagB64 || !$ciphertextB64 || !$privatePem) {
            http_response_code(400);
            echo json_encode(['message' => $translate->translate('Não é permitido campos em branco!', $lg_code), 'code' => 3]);
            exit;
        }
        $iv = base64_decode($ivB64);
        $tag = base64_decode($tagB64);
        $ciphertext = base64_decode($ciphertextB64);

        $aesKey = $this->getDecryptKey($encryptedKeyB64, $privatePem);

        // decrypt AES-GCM
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $aesKey, OPENSSL_RAW_DATA, $iv, $tag);
        if ($plaintext === false) {
            http_response_code(400);
            echo json_encode(['message' => $translate->translate('Erro de autenticação!', $lg_code), 'code' => 3]);
            exit;
        }

        // aqui você tem o JSON original do app (exemplo)
        $data = json_decode($plaintext, true); // se você enviou JSON puro

        return $data;
    }

    function getDecryptKey($encryptedKeyB64 = null, $privatePemB64 = null) {
        $translate = new Translate();

        // Configura idioma (só para mensagens de erro)
        $langObj = new Language();
        $langObj->setActive("1");
        $langs = $langObj->getAll();
        $cr = $langs[0];
        $lg_code = $cr->getCode();

        // Verifica parâmetros
        if (!$encryptedKeyB64 || !$privatePemB64) {
            http_response_code(400);
            echo json_encode([
                'message' => $translate->translate('Não é permitido campos em branco!', $lg_code),
                'code' => 3
            ]);
            exit;
        }

        // Obtém chave privada (decodificando Base64)
        $privatePem = base64_decode($privatePemB64);
        //   die($privatePemB64);
// Reconstrói PEM
// Obtém chave privada
        $privateKey = openssl_pkey_get_private($privatePem);
        if (!$privateKey) {
            die("Chave privada inválida!");
        }
        if (!$privateKey) {
            http_response_code(400);
            echo json_encode([
                'message' => $translate->translate('Chave privada inválida!', $lg_code),
                'code' => 3
            ]);
            exit;
        }
        // Decodifica chave AES criptografada
        $encryptedKey = base64_decode($encryptedKeyB64);
        //die($encryptedKey);
        // Descriptografa chave AES usando RSA OAEP
        $aesKey = null;
        $ok = openssl_private_decrypt($encryptedKey, $aesKey, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);
        if (!$ok) {
            //   die("Falha na descriptografia: " . openssl_error_string());
        }
        if (!$ok) {
            http_response_code(400);
            echo json_encode([
                'message' => $translate->translate('Erro de autenticação!', $lg_code),
                'code' => 3
            ]);
            exit;
        }
        
        /*$config = new McConfig();
        $myFile = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/log/" . "log.txt";
        $fh = fopen($myFile, 'w');
        $stringData = ["aes" => $aesKey, "key" => $privatePem];
        $stringData = json_encode($stringData);
        fwrite($fh, $stringData);
        fclose($fh);*/
        // Retorna chave AES pura
        return $aesKey;
    }
}
