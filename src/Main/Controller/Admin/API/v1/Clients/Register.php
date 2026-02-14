<?php

namespace Microfw\Src\Main\Controller\Admin\API\v1\Clients;

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Firebase\JWT\JWT;

$language = new Language;
$translate = new Translate();
// Chave secreta usada para assinar o token
define('SECRET_KEY', 'd469f8f1-2449-4cfa-a2bc-2465479b9c57');

/**
 * Description of Register
 *
 * @author Ricardo Gomes
 */
class Register {

    public static function register($email, $passwd, $passwd_confirmation, $name) {

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
        if ($email !== "" && $passwd !== "" && $passwd_confirmation !== "" && $name !== "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if ($passwd === $passwd_confirmation) {

                    $customers = new Customers();
                    $customers->setEmail($email);
                    $customers = $customers->getAll();
                    if (count($customers) > 0) {
                        http_response_code(401);
                        return json_encode(['message' => 'E-mail já cadastrado!', 'code' => 4]);
                    } else {
                        $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                        $pass = hash('sha512', $passwd . $salt);

                        $customer = new Customer();
                        $customer->setName($name);
                        $customer->setEmail($email);
                        $customer->setSalt($salt);
                        $customer->setPasswd($pass);
                        for ($i = 0; $i < 1000000000000; $i++) {
                            $cs = new Customer();
                            $cs->setGcid();
                            $tempCs = $cs->getAll();
                            $countCs = count($tempCs);
                            if ($countCs == 0) {
                                $customer->setGcid($cs->getGcid());
                                break;
                            }
                        }
                        $dateStart = $customer->getDateTime();
                        $customer->setDate_start($dateStart);

                        $dateEnd = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($dateStart)));
                        $customer->setDate_end($dateEnd);

                        $customer->setStatus(1);

                        $data = array(
                            'gcid' => $customer->getGcid(),
                            'name' => $name,
                            'email' => $email,
                            'exp' => time() + 2629743 // Expiração em 1 mes
                        );
                        $token = JWT::encode($data, SECRET_KEY, 'HS256');
                        //$token = JWT::encode($data, SECRET_KEY);

                        $customer->setAuth_token($token);
                        $customer->setSave();
                        return json_encode(['message' => 'Cadastro realizado com sucesso!', 'auth_token' => $token, 'name' => $name, 'email' => $email, 'code' => 1]);
                    }
                } else {
                    http_response_code(401);
                    return json_encode(['message' => $translate->translate('A senha e confirmação não conferem!', $lg_code), 'code' => 2]);
                }
            } else {
                http_response_code(401);
                return json_encode(['message' => $translate->translate('O e-mail fornecido é inválido!', $lg_code), 'code' => 7]);
            }
        } else {
            http_response_code(401);
            return json_encode(['message' => $translate->translate('Não é permitido campos em branco!', $lg_code), 'code' => 3]);
        }
    }
}
