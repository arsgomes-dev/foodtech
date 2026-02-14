<?php

session_start();

//Função para exclusão de mensagens do TICKET
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa as configurações do SITE
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_send_delete", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST) {
        if (!empty($_POST['code']) && $_POST['code']) {
            $code = $_POST['code'];
            //Declara classe e informa qual objeto será excluído do DB
            $message = new TicketSend;
            $message->setId($code);
            //Declara classe e consulta se a mensagem possui arquivos anexados, caso sim parte para exclusão
            $messageFile = new TicketSend;
            $messageFile = $messageFile->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $code]]);
            if ($messageFile->getFile() !== "" && $messageFile->getFile() !== null) {
                //Declara classes e consulta o GCID do TICKET e Do CLIENTE
                $ticketFile = new Ticket;
                $ticketFile = $ticketFile->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $messageFile->getTicket_id()]]);
                $customerFile = new Customers;
                $customerFile = $customerFile->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticketFile->getCustomer_id()]]);
                //informa o diretório do arquivo a ser excluído
                $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customerFile->getGcid() . "/tickets/" . $ticketFile->getGcid() . "/";
                //seta a classe que efetua as ações com arquivos
                $upload = new UploadImg;
                //executa a exclusão
                $upload->delete($dir_base, $messageFile->getFile());
            }
            //chama a função DELETE que executa o DELETE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $message->setDeleteQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Mensagem excluída com sucesso!', $_SESSION['user_lang']);
            } else {
                echo "2->" . $translate->translate('Erro ao excluir mensagem!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Selecione a mensagem que deseja excluir!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para excluir a(s) mensagem(s)!', $_SESSION['user_lang']);
}