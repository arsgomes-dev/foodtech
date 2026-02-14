<?php

session_start();

//Função para cadastrar/atualizar USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;
use Microfw\Src\Main\Controller\Admin\Users\Controller\GetEmail;
use Microfw\Src\Main\Controller\Admin\Users\Controller\GetCpf;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa configurações do site
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("user_create", $privilege_types) && in_array("user_edit", $privilege_types)) {
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['name']) && isset($_POST['name']) &&
            !empty($_POST['cpf']) && isset($_POST['cpf']) &&
            !empty($_POST['birth']) && isset($_POST['birth']) &&
            !empty($_POST['contact']) && isset($_POST['contact']) &&
            !empty($_POST['email']) && isset($_POST['email']) &&
            !empty($_POST['department']) && isset($_POST['department']) &&
            !empty($_POST['occupation']) && isset($_POST['occupation']) &&
            !empty($_POST['privileges']) && isset($_POST['privileges'])) {

        $code = "";
        if (isset($_POST['gcid'])) {
            $code = $_POST['gcid'];
        }
        //Cria instância da classe e verifica se o e-mail informado já esta cadastrado
        $searchEmail = new GetEmail;
        if ($searchEmail->getEmailRegistered($_POST['email'], $code) === false) {
            //verifica se o CPF informado já esta cadastrado
            $searchCpf = new GetCpf;
            if ($searchCpf->getCpfRegistered($_POST['cpf'], $code) === false) {
                if (!isset($_POST['gcid'])) {
                    //Caso o usuário não exista é setado um GCID único
                    $user = new User();
                    $gcid_bool = false;
                    $user->setGcid();
                    $gcid = $user->getGcid();
                    while (!$gcid_bool) {
                        $user_gcid = new User();
                        $user_gcid->setTable_db_primaryKey("gcid");
                        $user_gcid = $user_gcid->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gcid]]);
                        if (!empty($user_gcid->getGcid())) {
                            $user->setGcid();
                            $gcid = $user->getGcid();
                            $gcid_bool = false;
                        } else {
                            $gcid_bool = true;
                            break;
                        }
                    }
                    //Verifica se existe diretório de usuário para esse GCID, caso não tenha, ele é criado
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/user/" . $user->getGcid())) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublic() . $config->getBaseFileAdmin() . "/user/" . $user->getGcid(), 0777);
                        mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/user/" . $user->getGcid() . "/photo", 0777);
                        mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/user/" . $user->getGcid() . "/wallpaper", 0777);
                    }
                    //Cria um código SALT
                    $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                    //gera uma variavél aleatória
                    $number_of_bytes = 4;
                    $result_bytes = random_bytes($number_of_bytes);
                    //converte o resultado anterior $result_bytes (binários) em sua representação hexadecimal
                    $new_pass = bin2hex($result_bytes);
                    //gera um hash MD5 do resultado anterior $new_pass
                    $pass_md5 = md5($new_pass);
                    //cria um resultado criptográfico sha512 da junção $pass_md5 e do $salt
                    $passwd = hash('sha512', $pass_md5 . $salt);
                    //seta os valores padrões
                    //moeda padrão REAL
                    $user->setCurrency_id(1);
                    //Permite acesso a área administrativa
                    $user->setAdministrative(1);
                    //Linguagem padrão PT-BR
                    $user->setLanguage_id(1);
                    //Seta Status
                    $user->setStatus(1);
                    //Seta senha e salt
                    $user->setPasswd($passwd);
                    $user->setSalt($salt);
                }
                //seta informações na classe
                $user->setName($_POST['name']);
                $user->setCpf($_POST['cpf']);
                $user->setBirth($_POST['birth']);
                $user->setContact($_POST['contact']);
                $user->setEmail($_POST['email']);
                $user->setDepartment_id($_POST['department']);
                $user->setDepartment_occupation_id($_POST['occupation']);
                $user->setPrivilege_id($_POST['privileges']);

                //chama a função SAVE que executa o INSERT/UPDATE no DB
                $return = $user->setSaveQuery();

                if ($return == 1) {
                    echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
                } else if ($return == 2) {
                    //Caso seja cadastro irá enviar um e-mail para o usuário com a snehora provisória 
                    //caso tenha um retorno positivo, o sistema verifica se a mensagem de notificação esta ativa 
                    $notificationSearch = new Notification;
                    //consulta a notificação adequada para o cadastro de usuário
                    $notification = $notificationSearch->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "user_created"]]);
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
                    //define data e hora do e-mail
                    date_default_timezone_set('America/Bahia');
                    $date = date('d-m-Y');
                    $hour = date('H:i', time());
                    $pattern = array('{{{user.name}}}', '{{{user.password}}}', '{{{user.date}}}', '{{{user.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
                    $replacement = array($user->getName(), $new_pass, $date, $hour, $title_website, $endereco_http);
                    $subject = $notification->getTitle();
                    $messageSend = $notification->getDescription();
                    for ($i = 0; $i < count($pattern); $i++) {
                        $subject = preg_replace($pattern[$i], $replacement[$i], $subject);
                        $messageSend = preg_replace($pattern[$i], $replacement[$i], $messageSend);
                    }
                    //declara classe CronEmail, classe responsável por gerenciar os e-mails enviados pelo sistema, para não haver sobrecarga do servidor
                    $cron = new CronEmail();
                    //informa o e-mail do destinatário 
                    $cron->setEmail($user->getEmail());
                    //informa o nome de destinatário 
                    $cron->setNamemailer($user->getName());
                    //informa o assunto
                    $cron->setSubject($subject);
                    //informa a mensagem
                    $cron->setMessagesend($messageSend);
                    //seta o status de envio para 1 (true), status que informa que deverá ser enviado
                    $cron->setStatus(1);
                    //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                    $returnCron = $cron->setSaveQuery();
                    if ($returnCron == 2) {
                        echo "1->" . $translate->translate('"Cadastrado com sucesso, será enviado uma senha provisória em até 1 minuto para o e-mail do usuário cadastrado!', $_SESSION['user_lang']);
                    } else {
                        echo "1->" . $translate->translate('"Cadastrado com sucesso, erro ao enviar e-mail com senha provisória, tente novamente no cadastro do usuário!', $_SESSION['user_lang']);
                    }
                } else if ($return == 3) {
                    echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
                }
            } else {
                echo "2->" . $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']) . "!";
            }
        } else {
            echo "2->" . $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']) . "!";
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   