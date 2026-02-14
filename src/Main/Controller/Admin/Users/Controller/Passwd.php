<?php

session_start();

//Função para gerar uma senha temporária de acesso do USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão.
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa dos dados de confugurações do sistema
$config = new McConfig();
//verifica se o usuário tem privilégio de acesso a função
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("user_create", $privilege_types) && in_array("user_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code'])) {
        // Cria instância da classe e consulta pelo GCID
        $code = $_POST['code'];
        $userSelect = new User();
        $userSelect->setTable_db_primaryKey("gcid");
        $userSelect = $userSelect->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        // Cria instância da classe e seta informações
        $user = new User();
        $user->setId($userSelect->getId());
        //trás do DB a variável SALT já atribuida ao cliente que é usada para compor a criptografia da senha
        $salt = $userSelect->getSalt();
        //gera uma variavél aleatória
        $number_of_bytes = 4;
        $result_bytes = random_bytes($number_of_bytes);
        //converte o resultado anterior $result_bytes (binários) em sua representação hexadecimal
        $new_pass = bin2hex($result_bytes);
        //gera um hash MD5 do resultado anterior $new_pass
        $pass_md5 = md5($new_pass);
        //cria um resultado criptográfico sha512 da junção $pass_md5 e do $salt
        $passwd = hash('sha512', $pass_md5 . $salt);
        //seta a nova senha no banco de dados
        $user->setPasswd($passwd);
        //chama a função SAVE que executa o UPDATE no DB
        $return = $user->setSaveQuery();
        if ($return == 1) {
            //caso tenha um retorno positivo, o sistema verifica se a mensagem de notificação esta ativa 
            $notification = new Notification;
            //consulta a notificação adequada para a recuperação de senha do usuário
            $notification = $notification->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "user_recover_password"]]);
            //monta o link de retorno do Site para o cliente
            $endereco_http = $config->getDomainAdmin() . "/" . $config->getUrlAdmin();
            //atribui o titulo do siste salvo nas configurações
            $title_website = $config->getSiteTitle();
            // {{user.name}} -> nome do usuário
            // {{user.password}} -> senha provisória
            // {{user.date}} -> data
            // {{user.hour}} -> hora
            // {{website.title}} -> titulo do site (configurações)
            // {{website.http}} -> endereço http do site (configurações)
            //email a ser enviado
            date_default_timezone_set('America/Bahia');
            $date = date('d-m-Y');
            $hour = date('H:i', time());
            //informa quais variáveis das notificações será alterada por qual variável do sistema de acordo com a ordem a seguir
            $pattern = array('{{{user.name}}}', '{{{user.password}}}', '{{{user.date}}}', '{{{user.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
            $replacement = array($userSelect->getName(), $new_pass, $date, $hour, $title_website, $endereco_http);
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
            $cron->setEmail($userSelect->getEmail());
            //informa o nome de destinatário 
            $cron->setNamemailer($userSelect->getName());
            //informa o assunto
            $cron->setSubject($subject);
            //informa a mensagem
            $cron->setMessagesend($messageSend);
            //seta o status de envio para 1 (true), status que informa que deverá ser enviado
            $cron->setStatus(1);
            //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $returnCron = $cron->setSaveQuery();
            if ($returnCron == 2) {
                echo "1->" . $translate->translate('Senha recuperada com sucesso, será enviado uma senha provisória em até 1 minuto para o e-mail do usuário cadastrado!', $_SESSION['user_lang']);
            } else {
                echo "2->" . $translate->translate('Erro ao enviar mensagem!!', $_SESSION['customer_lang']);
            }
        } else if ($return == 3) {
            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   