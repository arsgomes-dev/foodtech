<?php

session_start();

//Função para gerar uma senha temporária de acesso do CLIENTE
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão.
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa dos dados de confugurações do sistema
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['customer_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("customer_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code'])) {

        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customerSelect = new Customers();
        //recebe o GCID do FRONTEND
        $code = $_POST['code'];
        //informa que a busca no banco será atráves do GCID e não do ID
        $customerSelect->setTable_db_primaryKey("gcid");
        //realiza a consulta no DB e retorna com linha única
        $customerSelect = $customerSelect->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        //verifica se o cadastro do cliente existe no banco de dados
        if ($customersOne !== null && $customersOne->getId() !== null && $customersOne->getId() > 0) {
            //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
            $customer = new Customers();
            //seta o ID pela consulta feita anteriormente 
            $customer->setId($customerSelect->getId());
            //trás do DB a variável SALT já atribuida ao cliente que é usada para compor a criptografia da senha
            $salt = $customerSelect->getSalt();
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
            $customer->setPasswd($passwd);
            //chama a função SAVE que executa o UPDATE no DB
            $return = $customer->setSaveQuery();
            //verifica o retorno
            if ($return == 1) {
                //caso tenha um retorno positivo, o sistema verifica se a mensagem de notificação esta ativa 
                $notificationSearch = new Notification;
                //consulta a notificação adequada para a recuperação de senha do cliente
                $notificationSearch->setDescription_type("customer_recover_password");
                $notifications = $notificationSearch->getQuery(limit: 1);
                $notificationsCount = count($notifications);
                $notification = new Notification;
                $notification = $notifications[0];
                //monta o link de retorno do Site para o cliente
                $endereco_http = $config->getDomainAdmin() . "/" . $config->getUrlAdmin();
                //atribui o titulo do siste salvo nas configurações
                $title_website = $config->getSiteTitle();
                //informações sobre o que cada variável representa
                // {{customer.name}} -> nome do usuário
                // {{customer.password}} -> senha provisória
                // {{customer.date}} -> data
                // {{customer.hour}} -> hora
                // {{website.title}} -> titulo do site (configurações)
                // {{website.http}} -> endereço http do site (configurações)
                //email a ser enviado
                //define data e hora do e-mail
                date_default_timezone_set('America/Bahia');
                $date = date('d-m-Y');
                $hour = date('H:i', time());
                //informa quais variáveis das notificações será alterada por qual variável do sistema de acordo com a ordem a seguir
                $pattern = array('{{{customer.name}}}', '{{{customer.password}}}', '{{{customer.date}}}', '{{{customer.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
                $replacement = array($customerSelect->getName(), $new_pass, $date, $hour, $title_website, $endereco_http);
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
                $cron->setEmail($customerSelect->getEmail());
                //informa o nome de destinatário 
                $cron->setNamemailer($customerSelect->getName());
                //informa o assunto
                $cron->setSubject($subject);
                //informa a mensagem
                $cron->setMessagesend($messageSend);
                //seta o status de envio para 1 (true), status que informa que deverá ser enviado
                $cron->setStatus(1);
                //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                $returnCron = $cron->setSaveQuery();
                if ($returnCron == 2) {
                    echo "1->" . $translate->translate('Senha recuperada com sucesso, será enviado uma senha provisória em até 1 minuto para o e-mail do usuário cadastrado!', $_SESSION['customer_lang']);
                } else {
                    echo "2->" . $translate->translate('Erro ao enviar mensagem!!', $_SESSION['customer_lang']);
                }
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['customer_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Cliente não encontrado!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['customer_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['customer_lang']);
}   