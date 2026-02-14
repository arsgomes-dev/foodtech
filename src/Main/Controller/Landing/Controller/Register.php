<?php

namespace Microfw\Src\Main\Controller\Landing\Controller;

session_start();

//Função para consultar se E-MAIL esta cadastrado para outro usuário, A intenção é bloquear cadastros com e-mails duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Controller\Landing\Controller\GetEmail;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\CronEmail;
use Microfw\Src\Main\Common\Entity\Public\Notification;

$config = new McClientConfig;

//Importa configurações do site

class Register {

    function setRegister($nome, $cpf, $celular, $cep, $logradouro, $complemento, $numero, $bairro, $cidade, $uf, $email, $passwd, $passwd_conf, $terms, $birth) {

        $translate = new Translate();
        $config = new McClientConfig();
        $email = trim($email ?? '');

        $lg = new Language;
        $lg = $lg->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
        $lang = $lg->getCode();
        $lang_locale = $lg->getLocale();
        $language = 1;

// 3. Validação de Sintaxe (Formato texto@texto.com)
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['status' => 'invalid_format', 'message' => 'Formato inválido']);
            exit;
        }

// -----------------------------------------------------------
// NOVO: Validação de Domínio (DNS/MX)
// -----------------------------------------------------------
// Pega tudo que vem depois do @
        $domain = substr(strrchr($email, "@"), 1);

// checkdnsrr: Verifica se existe registro de troca de e-mail (MX) no DNS
        if (!checkdnsrr($domain, 'MX')) {
            return json_encode([
                'status' => 'invalid_dns',
                'message' => "O domínio @$domain não parece válido."
            ]);
            exit;
        }

        $emailSearch = new GetEmail;

        // Verifica se existe
        if ($emailSearch->getEmailRegistered($email)) {
            return json_encode(['status' => 'exists',
                'message' => "O e-mail já possui cadastro."]);
        } else {

            //Cria um código SALT
            $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
            $passwd_hash = hash('sha512', $passwd . $salt);
            $passwd_conf_hash = hash('sha512', $passwd_conf . $salt);
            //seta os valores padrões

            if ($passwd_hash !== $passwd_conf_hash) {
                return json_encode(['status' => 'pass_invalid',
                    'message' => "A senha e a confirmação não conferem."]);
            }

            $customer = new Client();
            $gcid_bool = false;
            $customer->setGcid();
            $gcid = $customer->getGcid();
            while (!$gcid_bool) {
                $customer_gcid = new Client();
                $customer_gcid->setTable_db_primaryKey("gcid");
                $customer_gcid = $customer_gcid->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gcid]]);
                if ($customer_gcid !== null) {
                    $customer->setGcid();
                    $gcid = $customer->getGcid();
                    $gcid_bool = false;
                } else {
                    $gcid_bool = true;
                    break;
                }
            }

            $client = new Client;
            $client->setGcid($gcid);
            $client->setName($nome);
            $client->setCpf($cpf);
            $client->setBirth($birth);
            $client->setContact($celular);
            $client->setAndress_cep($cep);
            $client->setAndress_avenue($logradouro);
            $client->setAndress_number($numero);
            $client->setAndress_complement($complemento);
            $client->setAndress_city($cidade);
            $client->setAndress_neighborhood($bairro);
            $client->setAndress_state($uf);
            $client->setEmail($email);
            $client->setPasswd($passwd_hash);
            $client->setSalt($salt);
            $client->setStatus(1);
            $client->setTerms($terms);
            $client->setLanguage_id($lg->getId());

            //chama a função SAVE que executa o INSERT/UPDATE no DB
            $return = $client->setSaveQuery();

            if ($return == 2) {
                //Caso seja cadastro irá enviar um e-mail para o usuário com a snehora provisória 
                //caso tenha um retorno positivo, o sistema verifica se a mensagem de notificação esta ativa 
                $notificationSearch = new Notification;
                //consulta a notificação adequada para o cadastro de usuário
                $notification = $notificationSearch->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "customer_created"]]);
                //monta o link de retorno do Site para o cliente
                $endereco_http = $config->getDomain() . "/" . $config->getUrlPublic();
                //atribui o titulo do siste salvo nas configurações
                $title_website = $config->getSiteTitle();
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

// 1. GARANTIR QUE NENHUM VALOR SEJA NULL (Corrige o erro Deprecated)
// O operador ?? '' garante que se for null, vira uma string vazia
                $clientName = $client->getName() ?? '';
                $clientEmail = $client->getEmail() ?? '';
                $titleSite = $title_website ?? '';
                $httpAddr = $endereco_http ?? '';

                $pattern = array('{{customer.name}}', '{{customer.date}}', '{{customer.hour}}', '{{website.title}}', '{{website.http}}');
                $replacement = array($clientName, $date, $hour, $titleSite, $httpAddr);

                $subject = $notification->getTitle();
                $messageSend = $notification->getDescription();

// 2. SUBSTITUIÇÃO OTIMIZADA (Troca preg_replace por str_replace)
// str_replace aceita arrays diretamente, não precisa do loop 'for'
                $subject = str_replace($pattern, $replacement, $subject);
                $messageSend = str_replace($pattern, $replacement, $messageSend);

                // Declara classe CronEmail
                $cron = new CronEmail();

                // Informa o e-mail (Agora garantimos que não é null)
                $cron->setEmail($clientEmail);

                // Informa o nome
                $cron->setNamemailer($clientName);

                // Informa o assunto e mensagem processados
                $cron->setSubject($subject);
                $cron->setMessagesend($messageSend);

                // Status para envio
                $cron->setStatus(1);

                // Salva no DB
                $cron->setSaveQuery();

                $clientSearch = new Client;
                $clientSearch = $clientSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gcid]]);
                $client_id = $clientSearch->getId();
                //registra em SESSION
                $client_browser = $_SERVER['HTTP_USER_AGENT'];
                $client_id = preg_replace("/[^0-9]+/", "", $client_id);
                $_SESSION['logged_in'] = true;
                $_SESSION['client_id'] = $client_id;
                $_SESSION['client_gcid'] = $gcid;
                $_SESSION['client_customername'] = $nome;
                $_SESSION['client_photo'] = "";
                $_SESSION['client_language'] = $language;
                $_SESSION['client_lang'] = $lang;
                $_SESSION['client_lang_locale'] = $lang_locale;
                $_SESSION['client_login_string'] = hash('sha512', $passwd_hash . $client_browser);
                $_SESSION['client_premium'] = 0;
                $_SESSION['client_token_ai'] = "";
                $_SESSION['client_plan'] = "";
                $_SESSION['client_plan_code'] = "";
                $_SESSION['client_plan_title'] = "";
                $_SESSION['client_plan_tokens'] = "";
                $_SESSION['client_plan_scripts'] = "";
                $_SESSION['client_plan_channels'] = "";
                $_SESSION['client_plan_message'] = "";
                $_SESSION['client_plan_tokens_usage'] = "";
                $_SESSION['active_workspace_gcid'] = null;
                $_SESSION['active_workspace_thumb'] = null;
                $_SESSION['active_workspace_title'] = "Nenhuma selecionada";

                $clientSave = new Client();
                $clientSave->setId($client_id);
                $clientSave->setSession_date($clientSave->getDateTime());
                $clientSave->setSession_date_last($clientSave->getDateTime());
                $clientSave->setSaveQuery();

                //Verifica se existe diretório de usuário para esse GCID, caso não tenha, ele é criado
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid)) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid, 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/photo", 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/scripts", 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/tickets", 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/signatures", 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/youtube", 0777);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $gcid . "/youtube/cache", 0777);
                }



                return json_encode(['status' => 'registered']);
            } else if ($return == 3) {
                return json_encode(['status' => 'error',
                    'message' => "Erro ao realizar o cadastro, tente novamente!"]);
            }
        }
    }
}

?>