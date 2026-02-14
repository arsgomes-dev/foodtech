<?php

session_start();

//Função para atualização dos dados da Empresa administradora do sistema
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Company;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("configuration_company", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['cnpj']) && isset($_POST['cnpj']) &&
            !empty($_POST['contact']) && isset($_POST['contact']) &&
            !empty($_POST['email']) && isset($_POST['email']) &&
            !empty($_POST['company_name']) && isset($_POST['company_name']) &&
            !empty($_POST['fantasy_name']) && isset($_POST['fantasy_name']) &&
            !empty($_POST['municipal']) && isset($_POST['municipal']) &&
            !empty($_POST['start']) && isset($_POST['start']) &&
            !empty($_POST['cep']) && isset($_POST['cep']) &&
            !empty($_POST['avenue']) && isset($_POST['avenue']) &&
            !empty($_POST['number']) && isset($_POST['number']) &&
            !empty($_POST['neighborhood']) && isset($_POST['neighborhood']) &&
            !empty($_POST['city']) && isset($_POST['city']) &&
            !empty($_POST['state']) && isset($_POST['state'])) {
        //como essa tabela no banco de dados só tem uma linha, então verifica o código passado é 1, caso não, ele não executa, pois teve alteração maliciosa
        if ($_POST['code'] === "1") {
            //declara a classe em uma variável e preenche com as informações a serem salvas no DB            
            $company = new Company;
            $company->setId($_POST['code']);
            $company->setUser_id_updated($_SESSION['user_id']);
            $company->setCnpj($_POST['cnpj']);
            $company->setEmail($_POST['email']);
            $company->setContact(preg_replace('/\D/', '', $_POST['contact']));
            $company->setName_company($_POST['company_name']);
            $company->setName_fantasy($_POST['fantasy_name']);
            $company->setMunicipal_registration($_POST['municipal']);
            if (!empty($_POST['codeState']) && isset($_POST['codeState'])) {
                $company->setState_registration($_POST['codeState']);
            } else {
                $company->setState_registration("");
            }
            $company->setOpening($_POST['start']);
            $company->setAndress_cep($_POST['cep']);
            $company->setAndress_street($_POST['avenue']);
            $company->setAndress_number($_POST['number']);
            if (!empty($_POST['complement']) && isset($_POST['complement'])) {
                $company->setAndress_complement($_POST['complement']);
            } else {
                $company->setAndress_complement("");
            }
            $company->setAndress_neighbhood($_POST['neighborhood']);
            $company->setAndress_city($_POST['city']);
            $company->setAndress_state($_POST['state']);
            //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $company->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}