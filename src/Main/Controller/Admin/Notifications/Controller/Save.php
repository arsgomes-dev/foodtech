<?php

session_start();

//Função para cadastro e atualização das NOTIFICAÇÕES
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Notification;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("notification_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['notification']) && isset($_POST['notification'])) {
        //declara a classe em uma variável e preenche com as informações a serem salvas no DB
        $notification = new Notification;
        //caso seja informado o ID, a linha já existe então irá atualizar as informações     
        $notification->setId($_POST['notification']);
        //consulta a variável POST Status, se existe insere o valor correspondente, caso não coloca como 0 (false)
        if (!empty($_POST['sts'])) {
            if (isset($_POST['sts'])) {
                $notification->setStatus($_POST['sts']);
            } else {
                $notification->setStatus(0);
            }
        } else {
            $notification->setStatus(0);
        }

        //consulta a variável POST Titulo, se existe insere o valor correspondente
        if (!empty($_POST['title'])) {
            if (isset($_POST['title'])) {
                $notification->setTitle($_POST['title']);
            }
        }
        //consulta a variável POST Descrição, se existe insere o valor correspondente
        if (!empty($_POST['description'])) {
            if (isset($_POST['description'])) {
                $notification->setDescription($_POST['description']);
            }
        }
        //informa qual usuário administrativo executou a atualização 
        $notification->setUser_id($_SESSION['user_id']);
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $notification->setSaveQuery();
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