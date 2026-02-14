<?php

namespace Microfw\Src\Main\Controller\Public\Profile;

session_start();

//Função para consultasr se CPF esta cadastrado para outro usuário. A intenção é bloquear cadastros com CPFs duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão.
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

class GetCpf {

    function getCpfRegistered($cpf): bool {
        //Remove todos os caracteres especiais e mantém apenas os números.
        $cpfInformed = str_replace(array('.', '-', '/'), "", $cpf);
        //Consulta o usuário da SESSION
        if ($_SESSION['client_id']) {
            $client = new Client;
            //$client = $client->getOne($_SESSION['client_id']);
            $client = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);
            //Remove todos os caracteres especiais e mantém apenas os números.
            $cpfClientLogged = str_replace(array('.', '-', '/'), "", $client->getCpf());
            //verifica se o novo CPF informado é igual ao já registrado para o mesmo usuário e retorna FALSE
            if ($cpfInformed === $cpfClientLogged) {
                return false;
            }
        }
        //verifica se o novo CPF informado pertence a outro usuário caso sim retorna TRUE caso não retorna FALSE
        $clientSearch = new Client;
        // $clientSearch->setCpf($cpfInformed);
        //$clientSearch = $clientSearch->getCount();        
        // return $clientSearch->getTotal() > 0;
        $count = $clientSearch->getCountSumQuery(customWhere: [['column' => 'cpf', 'value' => $cpfInformed]]);
        return $count['total_count'] > 0;
    }
}
