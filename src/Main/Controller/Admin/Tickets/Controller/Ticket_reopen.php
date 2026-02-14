<?php

session_start();

//Função para reabrir o TICKET
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;
use Microfw\Src\Main\Common\Entity\Admin\Customers;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa as configurações do SITE
$config = new McConfig();
$stConfig = new StConfig();
$stConfig = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_reopen", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST) {
        if (!empty($_POST['code']) && $_POST['code']) {

            //Declara a classe TICKET em uma variável e realiza a consulta pelo GCID como primaryKey
            $ticketFind = new Ticket;
            $ticketFind->setTable_db_primaryKey("Gcid");
            $ticketFind = $ticketFind->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);
            //Declara a classe CUSTOMERS (CLIENTE) em uma variável e realiza a consulta pelo ID referênciado na classe TICKET
            $customer = new Customers;
            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticketFind->getCustomer_id()]]);
            //Define a data e hora da reabertura
            date_default_timezone_set('America/Bahia');
            $dateSend = date('d/m/Y');
            $hourSend = date('H:i', time());
            //declara a classe em uma variável e preenche com as informações a serem salvas no DB
            $ticket = new Ticket;
            $ticket->setId($ticketFind->getId());
            $ticket->setStatus(1);
            $ticket->setDate_closing(null);
            $ticket->setClosure_description("");
            $ticket->setUser_id_updated($_SESSION['user_id']);
            //consutar se o envio de notificação para o cliente sobre o ticket ser respondido esta ativo
            $notification = new Notification;
            $notification = $notification->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "ticket_reopen"]]);
            if ($notification !== null) {
                //Se o envio da notificação estiver ativa executa a ação
                if ($notification->getStatus() === 1) {
                    //monta o link de retorno do Site para o cliente
                    $endereco_http = $config->getDomain();
                    //atribui o titulo do siste salvo nas configurações
                    $title_website = $stConfig->getTitle();
                    // {{ticket.title}} -> titulo do ticket
                    // {{ticket.description}} -> mensagem original do ticket
                    // {{ticket.dateReopening}} -> data da criação do ticket
                    // {{ticket.hourReopening}} -> data da criação do ticket
                    // {{ticket.dateSend}} -> data do envio da resposta
                    // {{ticket.hourSend}} -> hora do envio da resposta
                    // {{website.title}} -> titulo do site (configurações)
                    // {{website.http}} -> endereço http do site (configurações)
                    //email a ser enviado
                    //informa quais variáveis das notificações será alterada por qual variável do sistema de acordo com a ordem a seguir
                    $pattern = array('{{{ticket.title}}}', '{{{ticket.description}}}', '{{{ticket.dateReopening}}}', '{{{ticket.hourReopening}}}', '{{{ticket.dateSend}}}', '{{{ticket.hourSend}}}', '{{{website.title}}}', '{{{website.http}}}');
                    $replacement = array($ticketFind->getTitle(), $ticketFind->getDescription(), $dateSend, $hourSend, $dateSend, $hourSend, $title_website, $endereco_http);
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
            $return = $ticket->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Ticket reaberto com sucesso!', $_SESSION['user_lang']);
            } else {
                echo "2->" . $translate->translate('Erro ao reabrir ticket!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para reabrir o Ticket!', $_SESSION['user_lang']);
}    