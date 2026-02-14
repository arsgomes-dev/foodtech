<?php

namespace Microfw\Src\Main\Controller\Public\Profile;

session_start();

//Função para consultar se E-MAIL esta cadastrado para outro usuário, A intenção é bloquear cadastros com e-mails duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig;
$planService = new CheckPlan;
$check = $planService->checkPlan();
if (!$check['allowed']) {
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}

class GetEmail {

    function getEmailRegistered($email): bool {
        //Consulta o usuário da SESSION
        if ($_SESSION['client_id']) {
            $client = new Client;
            //$client = $client->getOne($_SESSION['client_id']);
            $client = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);
            $currentEmail = $client->getEmail();
            //verifica se o novo E-MAIL informado é igual ao já registrado para o mesmo usuário e retorna FALSE
            if ($email === $currentEmail) {
                return false;
            }
        }
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
