<?php

namespace Microfw\Src\Main\Controller\Admin\API\v1\Clients;

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use \Firebase\JWT\JWT;

// Chave secreta usada para assinar o token
define('SECRET_KEY', 'd469f8f1-2449-4cfa-a2bc-2465479b9c57');

class Authentication {

    public function authentication($email, $passwd) {

        $translate = new Translate();
        $lang = new Language;
        $lang->setActive("1");
        $lang = $lang->getAll();
        $lang_count = count($lang);
        $lg = "";
        $lg_code = "";
        $cr = new Language;
        $cr = $lang[0];
        $lg = $cr->getId();
        $lg_code = $cr->getCode();

        if ($email !== "" && $passwd !== "") {
            $servePrivateKey = null; //define a variavel para armazenar a chave privada criada pelo servidor
            $customers = new Customers();
            $customers->setEmail($email);
            $customers = $customers->getAll();
            if (count($customers) > 0) {
                for ($i = 0; $i < count($customers); $i++) {
                    $customer = new Customers();
                    $customer = $customers[$i];
                    $salt = $customer->getSalt();
                    $password_db = $customer->getPasswd();
                    $password2 = hash('sha512', $passwd . $salt);
                    if ($password_db === $password2) {
                        $gcid = $customer->getGcid();
                        $name = $customer->getName();
                        $email = $customer->getEmail();
                        $premium = $customer->getIs_premium();
                        $data = array(
                            'gcid' => $gcid,
                            'name' => $name,
                            'email' => $email,
                            'exp' => time() + 2629743 // Expiração em 1 mes
                        );
                        $token = JWT::encode($data, SECRET_KEY, 'HS256');
                        $custs = new Customers();
                        $custs->setId($customer->getId());
                        $custs->setAuth_token($token);

                        $dateStart = $customer->getDateTime();
                        $custs->setDate_start($dateStart);

                        $dateEnd = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($dateStart)));
                        $custs->setDate_end($dateEnd);

                        // Gera par de chaves
                        $config = [
                            "digest_alg" => "sha256",
                            "private_key_bits" => 4096,
                            "private_key_type" => OPENSSL_KEYTYPE_RSA,
                        ];
                        $res = openssl_pkey_new($config);

                        // Extrai private key
                        openssl_pkey_export($res, $privateKey);
                        $custs->setPrivate_key(base64_encode($privateKey));
                        // Extrai public key
                        $publicKey = openssl_pkey_get_details($res)['key'];
                        //$publicKeyRSA = str_replace("-----BEGIN PUBLIC KEY-----", "", str_replace("-----END PUBLIC KEY-----", "", $publicKey));
                        $custs->setPublic_key(base64_encode($publicKey));
                        $custs->setSave();
                        // Criptografar resposta 
                        $response = ['message' => $translate->translate('Login bem-sucedido', $lg_code), 'auth_token' => $token, 'email' => $email, 'name' => $name, 'public' => base64_encode($publicKey), 'code' => 6];
                        return json_encode($response);
                    } else {
                        http_response_code(401);
                        return json_encode(['message' => $translate->translate('Login e/ou Senha incorreto(s)!', $lg_code), 'code' => 5]);
                    }
                }
            } else {
                http_response_code(401);
                return json_encode(['message' => $translate->translate('Login e/ou Senha incorreto(s)!', $lg_code), 'code' => 5]);
            }
        } else {
            http_response_code(401);
            return json_encode(['message' => $translate->translate('Não é permitido campos em branco!', $lg_code), 'code' => 3]);
        }
    }

    public static function hkdf_sha256($ikm, $length, $salt = '', $info = '') {
        // HKDF-Extract
        if ($salt === '') {
            $salt = str_repeat("\x00", 32); // hash length for SHA256
        }
        $prk = hash_hmac('sha256', $ikm, $salt, true);
        // HKDF-Expand
        $hash_len = 32;
        $n = ceil($length / $hash_len);
        $okm = '';
        $t = '';
        for ($i = 1; $i <= $n; $i++) {
            $t = hash_hmac('sha256', $t . $info . chr($i), $prk, true);
            $okm .= $t;
        }
        return substr($okm, 0, $length);
    }
}
