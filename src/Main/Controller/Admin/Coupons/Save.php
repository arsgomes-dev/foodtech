<?php

session_start();

//Função para cadastro e atualização dos CUPONS para serem utilizados no PLANO DE ASSINATURA
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlansCoupon;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("access_plans_coupons_create", $privilege_types) || in_array("access_plans_coupons_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['title']) && isset($_POST['title']) &&
            !empty($_POST['percentage']) && isset($_POST['percentage']) &&
            !empty($_POST['quantity']) && isset($_POST['quantity']) &&
            !empty($_POST['start']) && isset($_POST['start']) &&
            !empty($_POST['end']) && isset($_POST['end'])) {
        //declara a classe em uma variável e preenche com as informações a serem salvas no DB
        $accessPlan = new AccessPlansCoupon;
        if (!empty($_POST['code']) && isset($_POST['code'])) {
            //caso seja informado o ID, a linha já existe então irá atualizar as informações
            $accessPlan->setId($_POST['code']);
            //informa qual usuário administrativo executou a atualização 
            $accessPlan->setUser_id_updated($_SESSION['user_id']);
        } else {
            //caso não seja informado o ID, então irá cadastrar as informações, o GCID é um código de uso único e só é inserido em novas linhas, não deve ser atualizado
            $accessPlan->setGcid();
            //informa qual usuário administrativo executou o cadastro 
            $accessPlan->setUser_id_created($_SESSION['user_id']);
        }
        //preenche as informações a serem inseridas/atualizadas
        $accessPlan->setCoupon($_POST['title']);
        $accessPlan->setDiscount($_POST['percentage']);
        $accessPlan->setAmount_use($_POST['quantity']);
        $accessPlan->setDate_start($_POST['start']);
        $accessPlan->setDate_end($_POST['end']);
        //consulta a variável POST Status, se existe insere o valor correspondente, caso não coloca como 0 (false)
        if ($_POST['sts'] === "" || $_POST['sts'] === null || empty($_POST['sts']) || !isset($_POST['sts'])) {
            $accessPlan->setStatus(0);
        } else {
            $accessPlan->setStatus($_POST['sts']);
        }
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $accessPlan->setSaveQuery();
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