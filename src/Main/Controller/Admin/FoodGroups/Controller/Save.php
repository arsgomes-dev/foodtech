<?php

session_start();

//Função para cadastro e atualização dos DEPARTAMENTOS
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\FoodGroup;

$language = new Language;
$translate = new Translate();
$privilege_types = $_SESSION['user_type'];

if (in_array("food_group_create", $privilege_types) || in_array("food_group_edit", $privilege_types)) {
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['description']) && isset($_POST['description'])) {

        $group = new FoodGroup();

        if (!empty($_POST['code']) && isset($_POST['code'])) {
            $group->setId($_POST['code']);
            $group->setUser_id_updated($_SESSION['user_id']);
        } else {
            $group->setUser_id_created($_SESSION['user_id']);
        }
        $group->setDescription($_POST['description']);

        $return = $group->setSaveQuery();

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