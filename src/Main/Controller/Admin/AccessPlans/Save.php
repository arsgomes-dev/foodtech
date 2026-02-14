<?php

session_start();

//Função para cadastro e atualização dos dos PLANOS DE ASSINATURAS
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("access_plans_create", $privilege_types) || in_array("access_plans_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['title']) && isset($_POST['title']) &&
            !empty($_POST['descriptions_elements']) && isset($_POST['descriptions_elements']) &&
            !empty($_POST['price']) && isset($_POST['price']) &&
            !empty($_POST['start']) && isset($_POST['start']) &&
            !empty($_POST['end']) && isset($_POST['end']) &&
            !empty($_POST['validat']) && isset($_POST['validat'])) {
        //declara a classe em uma variável e preenche com as informações a serem salvas no DB
        $accessPlan = new AccessPlan;
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
        $accessPlan->setTitle($_POST['title']);
        //Essa estrutura de repetição (FOR) recebe as informações DESCRIÇÕES DO PLANO como array, concatena separadas por ponto e virgula e armazena em uma só variavél
        $description = "";
        $descriptions = $_POST['descriptions_elements'];
        $descriptionsCount = count($descriptions);
        if ($descriptionsCount > 0) {
            for ($i = 0; $i < $descriptionsCount; $i++) {
                if ($i === 0) {
                    $description .= $descriptions[$i];
                } else {
                    $description .= ";" . $descriptions[$i];
                }
            }
        }
        $accessPlan->setDescription($description);
        //Ribbon é a etiqueta opcional, então é testado aqui se será ou não inserida
        if (!empty($_POST['ribbon']) && isset($_POST['ribbon'])) {
            $accessPlan->setRibbon_tag($_POST['ribbon']);
        }
        //converte a formatação da moeda de acordo com o padrão escolhido. EX.: Dolar ou REAL
        $price = $translate->translateMonetaryDoubleLocale($_POST['price'], $_SESSION['user_currency_locale']);
        //preenche as informações a serem inseridas/atualizadas
        $accessPlan->setPrice($price);
        $accessPlan->setDate_start($_POST['start']);
        $accessPlan->setDate_end($_POST['end']);
        $accessPlan->setValidation($_POST['validat']);
        //consulta a variável POST observation
        if (!empty($_POST['observ']) && isset($_POST['observ'])) {
            $accessPlan->setObservation($_POST['observ']);
        }
        //consulta a variável POST tokens, se existe insere o valor correspondente, caso não coloca como 0
        if ($_POST['nTokens'] === "" || $_POST['nTokens'] === null || empty($_POST['nTokens']) || !isset($_POST['nTokens'])) {
            $accessPlan->setNumber_tokens(0);
        } else {
            $accessPlan->setNumber_tokens($_POST['nTokens']);
        }
        //consulta a variável POST scripts, se existe insere o valor correspondente, caso não coloca como 0
        if ($_POST['nScripts'] === "" || $_POST['nScripts'] === null || empty($_POST['nScripts']) || !isset($_POST['nScripts'])) {
            $accessPlan->setNumber_scripts(0);
        } else {
            $accessPlan->setNumber_scripts($_POST['nScripts']);
        }
        //consulta a variável POST channels, se existe insere o valor correspondente, caso não coloca como 0
        if ($_POST['nChannels'] === "" || $_POST['nChannels'] === null || empty($_POST['nChannels']) || !isset($_POST['nChannels'])) {
            $accessPlan->setNumber_channels(0);
        } else {
            $accessPlan->setNumber_channels($_POST['nChannels']);
        }

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