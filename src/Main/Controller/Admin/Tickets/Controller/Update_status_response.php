<?php

session_start();

//Função para atualizar status (ativo e inativo) da mensagem do TICKET
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("ticket_send_deactivate", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST) {
        if (!empty($_POST['code']) && !empty($_POST['status'])) {
            if ($_POST['code'] && $_POST['status']) {
                $status = $_POST['status'];
                //declara a classe em uma variável e preenche com as informações a serem salvas no DB
                $message = new TicketSend;
                $message->setId($_POST['code']);
                $message->setStatus($status);
                //chama a função SAVE que executa o UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                $return = $message->setSaveQuery();
                if ($return == 1) {
                    if ($status == 1) {
                        echo "1->" . $translate->translate('Mensagem ativada com sucesso!', $_SESSION['user_lang']);
                    } else {
                        echo "1->" . $translate->translate('Mensagem desativada com sucesso!', $_SESSION['user_lang']);
                    }
                } else {
                    echo "2->" . $translate->translate('Erro ao desativar mensagem!', $_SESSION['user_lang']);
                }
            } else {
                echo "2->" . $translate->translate('Selecione a mensagem que deseja desativar!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Selecione a mensagem que deseja desativar!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para desativar mensagem!', $_SESSION['user_lang']);
}