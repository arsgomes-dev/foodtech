<?php

session_start();

//Função para enviar mensagens no TICKET
//Insere a função que protege o script contra acessos 
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa as configurações do SITE
$config = new McConfig();
$stConfig = new StConfig;
$stConfig = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_send", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST && !empty($_POST['code']) && !empty($_POST['message_send']) && $_POST['code'] && $_POST['message_send']) {
        //Declara classe TICKET e realiza a consulta no banco de dados pelo ID
        $ticketFind = new Ticket;
        $ticketFind = $ticketFind->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_POST['code']]]);
        //Declara classe CUSTOMERS (CLIENTE) e realiza a consulta no banco de dados pelo ID
        $customer = new Customers;
        $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticketFind->getCustomer_id()]]);
        //Obtém a data do envio do ticket
        $date_receive = new DateTime($ticketFind->getDate_send());
        //Consulta da data e hora para o envio da mensagem
        date_default_timezone_set('America/Bahia');
        $dateSend = date('d/m/Y');
        $hourSend = date('H:i', time());
        //Consulta da data e hora em padrão MYSQL para salvar no DB
        $Data2 = date('Y-m-d ');
        $hora0 = date('H:i', time());
        $dataI = $Data2 . $hora0;
        //Declara classe TicketSend (Mensagens Enviadas do Ticket) e seta as informações         
        $message = new TicketSend();
        $message->setTicket_id($_POST['code']);
        $message->setUser_id($_SESSION['user_id']);
        $message->setMessage($_POST['message_send']);
        $message->setDate_send($dataI);
        $message_file = "";
        //Sendo positivo o envio, é conferido se a mensagem possui anexo
        if (!empty(array_filter($_FILES)) && $_FILES && $_FILES ["ticket_message_img"]['name']) {
            $input_name = "ticket_message_img";
            //Define diretório dos anexos do ticket
            $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/tickets/" . $ticketFind->getGcid();
            //Confere se o diretório existe, caso não é criado
            if (!file_exists($dir_base)) {
                mkdir($dir_base, 0777);
            }
            //Define a classe que retornará o resultado do upload
            $returning = new Returning;
            //Define a função que executa o upload do arquivo para o servidor
            $upload = new UploadImg;
            //Executa o upload
            $returning = $upload->uploadAll($dir_base . "/", $input_name, $_FILES [$input_name]);
            //Obtém o através da classe Renurning e executa a ação
            if ($returning->getValue() === 1) {
                $message_file = $returning->getDescription();
                $message->setFile($message_file);
            }
        }
        //consutar se o envio de notificação para o cliente sobre o ticket ser respondido esta ativo
        $notification = new Notification;
        $notification = $notification->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "ticket_response"]]);
        if ($notification !== null) {
            //Se o envio da notificação estiver ativa executa a ação
            if ($notification->getStatus() === 1) {
                //monta o link de retorno do Site para o cliente
                $endereco_http = $config->getDomain();
                //atribui o titulo do SITE salvo nas configurações
                $title_website = $stConfig->getTitle();
                // {{ticket.title}} -> titulo do ticket
                // {{ticket.description}} -> mensagem original do ticket
                // {{ticket.dateReceive}} -> data da criação do ticket
                // {{ticket.hourReceive}} -> data da criação do ticket
                // {{ticket.dateSend}} -> data do envio da resposta
                // {{ticket.hourSend}} -> hora do envio da resposta
                // {{website.title}} -> titulo do site (configurações)
                // {{website.http}} -> endereço http do site (configurações)
                //email a ser enviado
                //informa quais variáveis das notificações será alterada por qual variável do sistema de acordo com a ordem a seguir
                $pattern = array('{{{ticket.title}}}', '{{{ticket.description}}}', '{{{ticket.dateReceive}}}', '{{{ticket.hourReceive}}}', '{{{ticket.dateSend}}}', '{{{ticket.hourSend}}}', '{{{ticket.response}}}', '{{{website.title}}}', '{{{website.http}}}');
                $replacement = array($ticketFind->getTitle(), $ticketFind->getDescription(), $dateSend, $hourSend, $dateSend, $hourSend, $_POST['message_send'], $title_website, $endereco_http);
                //declara as variáveis Assunto e Descrição do E-mail
                $subject = $notification->getTitle();
                $messageSend = $notification->getDescription();
                //essa estrutura de repetição irá realizar as substituições das variavéis informadas anteriormente $pattern em $subject e $replacement em $messageSend
                for ($i = 0; $i < count($pattern); $i++) {
                    $subject = preg_replace($pattern[$i], $replacement[$i], $subject);
                    $messageSend = preg_replace($pattern[$i], $replacement[$i], $messageSend);
                }
                //declara classe CronEmail, classe responsável por gerenciar os e-mails enviados pelo sistema, para não haver sobrecarga do servidor
                $cron = new CronEmail();
                //informa o e-mail do destinatário 
                $cron->setEmail($customer->getEmail());
                //informa o nome de destinatário 
                $cron->setNamemailer($customer->getName());
                //informa o assunto
                $cron->setSubject($subject);
                //informa a mensagem
                $cron->setMessagesend($messageSend);
                //seta o status de envio para 1 (true), status que informa que deverá ser enviado
                $cron->setStatus(1);
                //salva no DB
                $cron->setSaveQuery();
            }
        }
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $message->setSaveQuery();
        if ($return == 2) {
            echo "1->" . $translate->translate('Mensagem enviada com sucesso!', $_SESSION['user_lang']);
        } else {
            //Caso ocorra um erro ao salvar o ticker e tenha sido feito upload de imagem, ela será excluída
            if (file_exists($dir_base . "/" . $message_file)) {
                //Define a função que executa o upload do arquivo para o servidor
                $upload = new UploadImg;
                //executa a exclusão
                $upload->delete($dir_base . "/", $messageFile->getFile());
            }
            echo "2->" . $translate->translate('Erro ao enviar mensagem!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para responder o Ticket!', $_SESSION['user_lang']);
}    