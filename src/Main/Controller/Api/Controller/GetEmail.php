<?php

namespace Microfw\Src\Main\Controller\Api\Controller;

session_start();

//Função para consultar se E-MAIL esta cadastrado para outro usuário, A intenção é bloquear cadastros com e-mails duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

$config = new McClientConfig;

class GetEmail {

    function getEmailRegistered($email): bool {
        //verifica se o novo E-MAIL informado pertence a outro usuário caso sim retorna TRUE caso não retorna FALSE
        $clientSearch = new Client;
        //$clientSearch->setEmail($email);
        $emailCount = new Client;
        //$emailCount = $clientSearch->getCount();
        $count = $clientSearch->getCountSumQuery(customWhere: [['column' => 'email', 'value' => $email]]);
        return $count['total_count'] > 0;
        // return $emailCount->getTotal() > 0;
    }
}
