<?php

session_start();

//Função para cadastro e atualização das OCUPAÇÕES
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("department_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST && !empty($_POST['title']) && isset($_POST['title']) && !empty($_POST['code']) && isset($_POST['code'])) {
        $occupation = new DepartmentOccupation();
        if (!empty($_POST['codeOccupation']) && isset($_POST['codeOccupation'])) {
            //caso seja informado o ID, a linha já existe então irá atualizar as informações      
            $occupation->setId($_POST['codeOccupation']);
            //informa qual usuário administrativo executou a atualização 
            $occupation->setUser_id_updated($_SESSION['user_id']);
        } else {
            //não tendo o ID será realizado um novo cadastro
            //informa qual usuário administrativo executou o cadastro 
            $occupation->setUser_id_created($_SESSION['user_id']);
            //informa no cadastro a qual departamento essa ocupação pertence
            $occupation->setDepartment_id($_POST['code']);
        }
        //seta as demais informações na classe
        $occupation->setTitle($_POST['title']);
        $occupation->setDepartment_id($_POST['code']);
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $occupation->setSaveQuery();
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