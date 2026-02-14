<?php

session_start();

//Função para exclusão do TICKET
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa as configurações do SITE
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_delete", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST) {
        if (!empty($_POST['code']) && $_POST['code']) {
            $code = $_POST['code'];
            //Declara classe e informa qual objeto será excluído do DB
            $ticket = new Ticket();
            $ticket->setId($code);
            //chama a função DELETE que executa o DELETE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $ticket->setDeleteQuery();
            if ($return == 1) {
                //em caso de exclusão bem sucedida excluir também as mensagens desse ticket
                $message = new TicketSend();
                //informa que será excluído de acordo com a variável TICKET_ID
                $message->setTable_db_primaryKey("ticket_id");
                $message->setTicket_id($code);
                $message->setDeleteQuery();
                echo "2->" . $translate->translate('Ticket excluído com sucesso!', $_SESSION['user_lang']);
            } else {
                echo "2->" . $translate->translate('Erro ao excluir ticket!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Selecione o ticket que deseja excluir!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para excluir o ticket!', $_SESSION['user_lang']);
}