<?php

session_start();

//Função para cadastro e atualização dos DEPARTAMENTOS
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\FoodBrand;

$language = new Language;
$translate = new Translate();
$privilege_types = $_SESSION['user_type'];

if (in_array("brand_create", $privilege_types) || in_array("brand_edit", $privilege_types)) {
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['description']) && isset($_POST['description'])) {

        $brand = new FoodBrand();

        if (!empty($_POST['code']) && isset($_POST['code'])) {
            $brand->setId($_POST['code']);
            $brand->setUser_id_updated($_SESSION['user_id']);
        } else {
            $brand->setUser_id_created($_SESSION['user_id']);
        }

        if ($_POST['status'] === "" || $_POST['status'] === null || empty($_POST['status']) || !isset($_POST['status'])) {
            $brand->setStatus(0);
        } else {
            $brand->setStatus($_POST['status']);
        }
        $brand->setDescription($_POST['description']);

        $return = $brand->setSaveQuery();

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