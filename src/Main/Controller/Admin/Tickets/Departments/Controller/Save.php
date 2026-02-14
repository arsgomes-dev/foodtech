<?php

session_start();

//Função para SALVAR/ATUALIZAR departamentos de atendimento do TICKET
//Insere a função que protege o script contra acessos 
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_department_create", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST) {
        if (!empty($_POST['title']) && isset($_POST['title'])) {
            //Declara a classe e seta as informações
            $ticket = new TicketDepartment;
            if (!empty($_POST['code']) && isset($_POST['code'])) {
                //Caso seja fornecido o ID é realizado a atualização das informações
                $ticket->setId($_POST['code']);
                //Informa qual usuário logado efetuou a atualização
                $ticket->setUser_id_updated($_SESSION['user_id']);
            } else {
                //Caso não seja informado um ID é realizado o cadastramento
                //Informa qual usuário logado realizou o cadastro
                $ticket->setUser_id_created($_SESSION['user_id']);
            }
            //Preenche com as demais informações
            if (!empty($_POST['title'])) {
                if (isset($_POST['title'])) {
                    $ticket->setTitle($_POST['title']);
                }
            }
            if (!empty($_POST['status'])) {
                if (isset($_POST['status'])) {
                    $ticket->setStatus($_POST['status']);
                } else {
                    $ticket->setStatus(0);
                }
            } else {
                $ticket->setStatus(0);
            }
            //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $ticket->setSaveQuery();
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
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}
