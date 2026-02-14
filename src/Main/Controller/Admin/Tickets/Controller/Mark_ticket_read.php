<?php

namespace Microfw\Src\Main\Controller\Admin\Tickets;

session_start();

//Função para marcar as mensagens e o TICKET como LIDO
//Insere a função que protege o script contra acessos 
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;

class MarkTicketRead {

    //Função que marca a mensagem pelo ID como LIDO
    function setMarkMessageRead($id): bool {
        // Cria instância da classe
        $ticket_read = new TicketSend;
        // Seta as informações
        $ticket_read->setId($id);
        // Define o fuso horário
        //date_default_timezone_set('America/Bahia');
        // Cria data e hora atuais
        $date_send = date('Y-m-d H:i:s');
        // Define a data de leitura
        $ticket_read->setDate_read($date_send);
        // Chama a função SAVE que executa o UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        return $ticket_read->setSaveQuery() === 1;
    }

    //Função que marca o ticket pelo ID como LIDO
    function setMarkTicketRead($id): bool {
        // Cria instância da classe
        $ticket = new Ticket;
        // Define os dados do ticket
        $ticket->setId($id);
        $ticket->setResponse(0);
        $ticket->setMessage_reading_status(0);
        $ticket->setUser_id_reading($_SESSION['user_id']);
        // Define o fuso horário
       // date_default_timezone_set('America/Bahia');
        // Cria data e hora atuais
        $date_reading = date('Y-m-d H:i:s');
        $ticket->setDate_reading($date_reading);
        //chama a função SAVE que executa o UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        return $ticket->setSaveQuery() === 1;
    }
}
