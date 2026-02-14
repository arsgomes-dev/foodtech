<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Admin\API\v1\Clients;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Exception;

define('SECRET_KEY', 'd469f8f1-2449-4cfa-a2bc-2465479b9c57');

/**
 * Description of Auth
 *
 * @author Ricardo Gomes
 */
class AuthRequired {

    function validateToken($token) {
        if ($token) {
            try {
                // Decodificar o token
                $key = new Key(SECRET_KEY, 'HS256'); // ---> passei a key desta forma
                $decoded = JWT::decode($token, $key);

                //$decoded = JWT::decode($token, SECRET_KEY, 'HS256');

                return (array) $decoded; // Retorna os dados decodificados do token
            } catch (Exception $e) {
                // Se o token for inválido ou expirado
                return 8;
            }
        } else {
            return 9;
        }
    }
}
