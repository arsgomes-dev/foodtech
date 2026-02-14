<?php

session_start();

//Função para cadastro e atualização dos preços do PLANO DE ASSINATURA
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlanPrice;
use Microfw\Src\Main\Common\Entity\Admin\Currency;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("access_plans_create", $privilege_types) || in_array("access_plans_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['plan']) && isset($_POST['plan']) &&
            !empty($_POST['price_currency']) && isset($_POST['price_currency']) &&
            !empty($_POST['coin_currency']) && isset($_POST['coin_currency']) &&
            !empty($_POST['start_currency']) && isset($_POST['start_currency']) &&
            !empty($_POST['end_currency']) && isset($_POST['end_currency'])) {
        //consulta no DB as informações da moeda que foi informada
        $currency = new Currency;        
        $currency = $currency->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_POST['coin_currency']]]);        
        //declara a classe em uma variável e preenche com as informações a serem salvas no DB
        $accessPlanPrice = new AccessPlanPrice;
        if (!empty($_POST['currency']) && isset($_POST['currency'])) {
            //caso seja informado o ID, a linha já existe então irá atualizar as informações
            $accessPlanPrice->setId($_POST['currency']);
            //informa qual usuário administrativo executou a atualização 
            $accessPlanPrice->setUser_id_updated($_SESSION['user_id']);
        } else {
            //caso não seja informado o ID, então irá cadastrar as informações
            //informa a qual plano de assinatura precente esse preço
            $accessPlanPrice->setAccess_plan_id($_POST['plan']);
            //informa qual usuário administrativo executou o cadastro 
            $accessPlanPrice->setUser_id_created($_SESSION['user_id']);
        }
        //converte a formatação da moeda de acordo com o padrão escolhido. EX.: Dolar ou REAL
        $price = $translate->translateMonetaryDoubleLocale($_POST['price_currency'], $currency->getLocale());
        //preenche as informações a serem inseridas/atualizadas
        $accessPlanPrice->setCurrency_id($currency->getId());
        $accessPlanPrice->setPrice($price);
        $accessPlanPrice->setDate_start($_POST['start_currency']);
        $accessPlanPrice->setDate_end($_POST['end_currency']);
        //consulta a variável POST Status, se existe insere o valor correspondente, caso não coloca como 0 (false)
        if ($_POST['sts_currency'] === "" || $_POST['sts_currency'] === null || empty($_POST['sts_currency']) || !isset($_POST['sts_currency'])) {
            $accessPlanPrice->setStatus(0);
        } else {
            $accessPlanPrice->setStatus($_POST['sts_currency']);
        }
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $accessPlanPrice->setSaveQuery();
        if ($return == 1) {
            echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 2) {
            echo "1->" . $translate->translate('Cadastro realizado com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 3) {
            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}