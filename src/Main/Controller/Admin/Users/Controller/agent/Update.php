<?php

session_start();

//Função para atualizar os status do USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentAgent;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("user_edit", $privilege_types)) {
//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code'])) {
        // Cria instância da classe e consulta pelo GCID
        $code = $_POST['code'];
        $userOne = new User();
        $userOne->setTable_db_primaryKey("gcid");
        $userOne = $userOne->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        if ($userOne !== null && $userOne->getId() !== null && $userOne->getId() > 0) {
            // Cria instância da classe e seta informações
            $user = new User();
            $user->setId($userOne->getId());
            if (isset($_POST['agent_status']) && !empty($_POST['agent_status'])) {
                $agent_status = $_POST['agent_status'];
                if ($agent_status === "1") {
                    //se o status do agente for positivo, atribui ao mesmo os subdepartamentos autorizados para atendimento 
                    $user->setStatus_agent($_POST['agent_status']);
                    if (isset($_POST['agent_subdepartment']) && !empty($_POST['agent_subdepartment'])) {
                        $subdepartment = $_POST['agent_subdepartment'];
                        $subdepartment_count = count($_POST['agent_subdepartment']);
                        // Cria instância da classe e exclui as atribuições anteriores 
                        $tickets = new TicketDepartmentSubdepartmentAgent();
                        $tickets->setTable_db_primaryKey("ticket_agent_id");
                        $tickets->setTicket_agent_id($userOne->getId());
                        $tickets->setDeleteQuery();
                        $tickets = null;
                        for ($i = 0; $i < $subdepartment_count; $i++) {
                            // Cria instância da classe e seta informações
                            $ticket_agent = new TicketDepartmentSubdepartmentAgent;
                            $ticket_agent->setLogTimestamp(false);
                            $ticket_agent->setTicket_agent_id($userOne->getId());
                            $ticket_agent->setTicket_department_subdepartment_id($subdepartment[$i]);
                            //salva no DB
                            $ticket_agent->setSaveQuery();
                        }
                    } else {
                        // Cria instância da classe e exclui as atribuições anteriores 
                        $tickets = new TicketDepartmentSubdepartmentAgent();
                        $tickets->setTable_db_primaryKey("ticket_agent_id");
                        $tickets->setTicket_agent_id($userOne->getId());
                        $tickets->setDeleteQuery();
                        $tickets = null;
                    }
                } else {
                    $user->setStatus_agent(0);
                }
            } else {
                $user->setStatus_agent(0);
            }
            //Salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $user->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Configurações de atendimento atualizado com sucesso!', $_SESSION['user_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao atualizar configurações de atendimento!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Usuário não encontrado!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   