<?php

session_start();

//Função para atualizar o despartamento do USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("user_edit", $privilege_types)) {
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['department']) && isset($_POST['department']) &&
            !empty($_POST['occupation']) && isset($_POST['occupation'])) {
        // Cria instância da classe e consulta pelo GCID
        $code = $_POST['code'];
        $userOne = new User();
        $userOne->setTable_db_primaryKey("gcid");
        $userOne = $userOne->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        if ($userOne !== null && $userOne->getId() !== null && $userOne->getId() > 0) {
            // Cria instância da classe e seta informações
            $user = new User();
            $user->setId($userOne->getId());
            $user->setDepartment_id($_POST['department']);
            $user->setDepartment_occupation_id($_POST['occupation']);
            //salva no DB
            $return = $user->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
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