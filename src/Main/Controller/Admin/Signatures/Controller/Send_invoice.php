<?php

session_start();

//Função para enviar NFSe para o cliente
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePaymentInvoice;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Currency;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\CronEmailNfse;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa as configurações do SITE
$config = new McConfig();
$stConfig = new StConfig();
$stConfig = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("customer_signatures", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['invoice']) && isset($_POST['invoice'])) {
        //declara variável e consulta pelo GCID
        $signaturePaymentInvoice = new SignaturePaymentInvoice();
        $signaturePaymentInvoice->setTable_db_primaryKey("gcid");
        $signaturePaymentInvoice = $signaturePaymentInvoice->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['invoice']]]);
        //verifica se existe registro para o PDF na tabela
        if (!empty($signaturePaymentInvoice->getInvoice_pdf()) && $signaturePaymentInvoice->getInvoice_pdf() !== null && $signaturePaymentInvoice->getInvoice_pdf() !== "") {
            $return = "";

            //declara a classe para consultar o pagamento da assinatura (SignaturePayment) e informa que será consultado pelo GCID
            $signaturePayment = new SignaturePayment();
            $signaturePayment->setTable_db_primaryKey("gcid");
            $signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);
            $gcid = $signaturePayment->getGcid();

            //declara a classe para consultar a assinatura (Signature) pelo ID salvo na tabela SignaturePayment
            $signature = new Signature;
            $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getSignature_id()]]);

            //declara a classe para consultar o plano de acesso
            $accessPlan = new AccessPlan;
            $accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);

            //declara a classe para consulta do cliente
            $customer = new Customers;
            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);

            //declara a classe para consultar a moeda cadastrada na assinatura
            $currency = new Currency;
            $currency = $currency->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

            //formata a data de faturamento
            $date_billing = "";
            if ($signaturePayment->getDate_billing() !== null && $signaturePayment->getDate_billing() !== "") {
                $date_billing = (new DateTime($signaturePayment->getDate_billing()))->format("d/m/Y");
            }

            //formata a data de pagamento
            $date_payment = "";
            if ($signaturePayment->getDate_payment() !== null && $signaturePayment->getDate_payment() !== "") {
                $date_payment = (new DateTime($signaturePayment->getDate_payment()))->format("d/m/Y");
            }
            //formata o preço de acordo com a moeda
            $price = $translate->translateMonetary($signature->getPrice(), $currency->getCurrency(), $currency->getLocale());
            //formata o desconto
            $discount = number_format($signature->getDiscount(), 2, ',', '.');
            //calcula o preço após desconto
            $total_price = $signature->getPrice() - ($signature->getPrice() * (number_format($signature->getDiscount(), 2) / 100));

            $urlConsultation = $signaturePaymentInvoice->getConsultation_url();

            //verifica se existe a variável PDF seta na classe
            $filesEmail = [];
            $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $gcid;
            $filesEmail[0] = $dir_base . "/" . $signaturePaymentInvoice->getInvoice_pdf();

            //consuta se o envio de notificação para o cliente sobre o a NFSE ser enviado esta ativo
            $notification = new Notification;
            $notification = $notificationSearch->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "client_subscription_nfse"]]);
            if ($notification !== null) {
                if ($notification->getStatus() === 1) {
                    //define data e hora do e-mail
                    date_default_timezone_set('America/Bahia');
                    $dateSend = date('d/m/Y');
                    $hourSend = date('H:i', time());
                    //monta o link de retorno do Site para o cliente
                    $endereco_http = $config->getDomain();
                    //atribui o titulo do siste salvo nas configurações
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
                    $pattern = array('{{{customer.name}}}', '{{{signature.plan}}}', '{{{signature.dateBilling}}}', '{{{signature.datePayment}}}', '{{{signature.pricePlan}}}', '{{{signature.discount}}}'
                        , '{{{signature.netAmount}}}', '{{{signature.url}}}', '{{{customer.date}}}', '{{{customer.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
                    $replacement = array($customer->getName(), $accessPlan->getTitle(), $date_billing, $date_payment, $price, $discount . "%",
                        $translate->translateMonetary($total_price, $currency->getCurrency(), $currency->getLocale()), $urlConsultation, $dateSend, $hourSend, $title_website, $endereco_http);
                    //declara as variáveis Assunto e Descrição do E-mail
                    $subject = $notification->getTitle();
                    $messageSend = $notification->getDescription();
                    //essa estrutura de repetição irá realizar as substituições das variavéis informadas anteriormente $pattern em $subject e $replacement em $messageSend
                    for ($i = 0; $i < count($pattern); $i++) {
                        $subject = preg_replace($pattern[$i], $replacement[$i], $subject);
                        $messageSend = preg_replace($pattern[$i], $replacement[$i], $messageSend);
                    }
                    //declara classe CronEmail, classe responsável por gerenciar os e-mails enviados pelo sistema, para não haver sobrecarga do servidor
                    $cron = new CronEmailNfse();
                    $cron->setSignature_payment_gcid($signaturePayment->getGcid());
                    //informa o e-mail do destinatário 
                    $cron->setEmail($customer->getEmail());
                    //informa o nome de destinatário 
                    $cron->setNamemailer($customer->getName());
                    //informa o assunto
                    $cron->setSubject($subject);
                    //informa a mensagem
                    $cron->setMessagesend($messageSend);
                    //seta os arquivos a serem enviados
                    $cron->setFiles(json_encode($filesEmail));
                    //seta o status de envio para 1 (true), status que informa que deverá ser enviado
                    $cron->setStatus(1);
                    //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                    $return = $cron->setSaveQuery();
                }
            } else {
                echo "2->" . $translate->translate('Erro ao realizar envio!', $_SESSION['user_lang']);
            }
            if ($return == 2) {
                echo "1->" . $translate->translate('NFS-e enviada com sucesso!', $_SESSION['user_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao realizar envio!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Anexe e salve o pdf da NFS-e antes do envio!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}    