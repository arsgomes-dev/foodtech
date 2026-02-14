<?php

session_start();

//Função para atualizar os dados do USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Controller\Admin\Users\Controller\GetEmail;
use Microfw\Src\Main\Controller\Admin\Users\Controller\GetCpf;
use Microfw\Src\Main\Common\Helpers\Admin\Validate\IsValidCpf;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("user_edit", $privilege_types)) {
//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['name']) && isset($_POST['name']) &&
            !empty($_POST['cpf']) && isset($_POST['cpf']) &&
            !empty($_POST['birth']) && isset($_POST['birth']) &&
            !empty($_POST['contact']) && isset($_POST['contact']) &&
            !empty($_POST['email']) && isset($_POST['email'])) {

        // Cria instância da classe e consulta pelo GCID
        $code = $_POST['code'];
        $userOne = new User();
        $userOne->setTable_db_primaryKey("gcid");
        $userOne = $userOne->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        //Verifica se o usuário existe no DB
        if ($userOne !== null && $userOne->getId() !== null && $userOne->getId() > 0) {
            $isValidCpf = new IsValidCpf;
            //Verifica se o CPF é válido
            if ($isValidCpf->getIsValidCPF($_POST['cpf'])) {
                // Cria instância da classe e verifica se E-MAIL já esta registrado para outro usuário
                $searchEmail = new GetEmail;
                if ($searchEmail->getEmailRegistered($_POST['email'], $code) === false) {
                    // Cria instância da classe e verifica se CPF já esta registrado para outro usuário
                    $searchCpf = new GetCpf;
                    if ($searchCpf->getCpfRegistered($_POST['cpf'], $code) === false) {
                        // Cria instância da classe e seta as informações na classe
                        $user = new User();
                        $user->setId($userOne->getId());
                        $user->setName($_POST['name']);
                        $user->setCpf($_POST['cpf']);
                        $user->setBirth($_POST['birth']);
                        $user->setContact(preg_replace('/\D/', '', $_POST['contact']));
                        $user->setEmail($_POST['email']);
                        //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                        $return = $user->setSaveQuery();
                        if ($return == 1) {
                            echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
                        } else if ($return == 3) {
                            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
                        }
                    } else {
                        echo "2->" . $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']) . "!";
                    }
                } else {
                    echo "2->" . $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']) . "!";
                }
            } else {
                echo "2->" . $translate->translate('CPF inválido', $_SESSION['user_lang']) . "!";
            }
        } else {
            echo "2->" . $translate->translate('Usuário não encontrado!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   