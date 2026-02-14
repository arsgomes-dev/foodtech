<?php

session_start();

//Função para insere/atualiza a NFSe e envia para o cliente
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
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\CronEmailNfse;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

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
            !empty($_POST['number']) && isset($_POST['number']) &&
            !empty($_POST['verification']) && isset($_POST['verification']) &&
            !empty($_POST['serie']) && isset($_POST['serie']) &&
            !empty($_POST['issue']) && isset($_POST['issue']) &&
            !empty($_POST['total']) && isset($_POST['total']) &&
            !empty($_POST['net']) && isset($_POST['net']) &&
            !empty($_POST['url']) && isset($_POST['url'])) {

        $filesEmail = [];
        //declara a classe para consultar o pagamento da assinatura (SignaturePayment) e informa que será consultado pelo GCID
        $signaturePayment = new SignaturePayment();
        $signaturePayment->setTable_db_primaryKey("gcid");
        $signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);
        $gcid = $signaturePayment->getGcid();

        //declara a classe para consultar a assinatura (Signature) pelo ID salvo na tabela SignaturePayment
        $signature = new Signature;
        $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getSignature_id()]]);

        //declara a classe para consultar a moeda cadastrada na assinatura
        $currency = new Currency;
        $currency = $currency->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

        //declara a classe para consultar o plano de acesso
        $accessPlan = new AccessPlan;
        $accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);

        //declara a classe para consulta do cliente
        $customer = new Customers;
        $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);
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
        //declara as variaveis para verificar os aquivos da NFSE PDF e XML
        $arq_invoice = false;
        $arq_xml = false;
        $file_invoice = "";
        $file_xml = "";
        //declara variável para inserir/atualizar NFSe no DB
        $payment = new SignaturePaymentInvoice();
        if (!empty($_POST['invoice']) && isset($_POST['invoice'])) {
            //caso a NFS-e ja exista é atualizado e informa que será atualizado pelo GCID
            $payment->setTable_db_primaryKey("gcid");
            $payment->setGcid($_POST['invoice']);
            //consulta para verificar se já existe NFS-e salva
            $paymentInvoiceSearch = new SignaturePaymentInvoice();
            $paymentInvoiceSearch->setTable_db_primaryKey("gcid");
            $paymentInvoiceSearch = $paymentInvoiceSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['invoice']]]);
            $arq_invoice = ($paymentInvoiceSearch->getInvoice_pdf() !== "" && $paymentInvoiceSearch->getInvoice_pdf() !== null) ? true : false;
            $file_invoice = ($paymentInvoiceSearch->getInvoice_pdf() !== "" && $paymentInvoiceSearch->getInvoice_pdf() !== null) ? $paymentInvoiceSearch->getInvoice_pdf() : "";
            $arq_xml = ($paymentInvoiceSearch->getInvoice_xml() !== "" && $paymentInvoiceSearch->getInvoice_xml() !== null) ? true : false;
            $file_xml = ($paymentInvoiceSearch->getInvoice_xml() !== "" && $paymentInvoiceSearch->getInvoice_xml() !== null) ? $paymentInvoiceSearch->getInvoice_xml() : "";
        } else {
            //insere um novo registro
            $payment->setGcid();
            $payment->setSignature_payment_gcid($_POST['code']);
        }
        //formata os preços pela moeda utilizado pelo usuário
        $price_total = $translate->translateMonetaryDoubleLocale($_POST['total'], $_SESSION['user_currency_locale']);
        $price_net = $translate->translateMonetaryDoubleLocale($_POST['net'], $_SESSION['user_currency_locale']);
        //insere informações a serem inseridas/atualizadas no DB
        $payment->setTotal_amount($price_total);
        $payment->setNet_amount($price_net);
        $payment->setNumber_invoice($_POST['number']);
        $payment->setSeries_invoice($_POST['serie']);
        $payment->setVerification_code($_POST['verification']);
        $payment->setDate_issue($_POST['issue']);
        $urlConsultation = $_POST['url'];
        $payment->setConsultation_url($urlConsultation);
        //Verifica se as variáveis relacionadas à existência de cancelamento estão devidamente preenchidas.
        if (!empty($_POST['canceled']) && isset($_POST['canceled'])) {
            $payment->setCanceled_at("canceled");
        }
        if (!empty($_POST['cancellation']) && isset($_POST['cancellation'])) {
            $payment->setCancel_reason($_POST['cancellation']);
        }
        //verifica se existe os arquivos PDF E XML
        $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $gcid;
        if (!empty(array_filter($_FILES)) && $_FILES) {
            //define os nomes das variáveis
            $input_invoice_name = "invoice_arq";
            $input_xml_name = "xml_arq";
            //define o diretorio dos arquivos
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices")) {
                if (!file_exists($dir_base)) {
                    mkdir($dir_base, 0777);
                }
            }

            //verifica se existe a variável PDF seta na classe
            if (isset($_FILES[$input_invoice_name])) {
                if ($_FILES [$input_invoice_name]['name'] && $arq_invoice === false && $gcid !== "" && $gcid !== null) {
                    //define a classe que retornará o resultado do upload
                    $returning = new Returning;
                    $upload = new UploadImg;
                    $returning = $upload->uploadFiles($dir_base . "/", $input_invoice_name, $_FILES [$input_invoice_name], "invoice_" . trim(preg_replace('/[^0-9]/', '', $payment->getDateTime())));
                    if ($returning->getValue() === 1) {
                        $payment->setInvoice_pdf($returning->getDescription());
                        $filesEmail[0] = $dir_base . "/" . $returning->getDescription();
                    }
                }
            }
            //verifica se existe a variável XML seta na classe
            if (isset($_FILES[$input_xml_name])) {
                if ($_FILES [$input_xml_name]['name'] !== null && $arq_xml === false && $gcid !== "" && $gcid !== null) {
                    //define a classe que retornará o resultado do upload
                    $returning = new Returning;
                    //Define a função que executa o upload do arquivo para o servidor
                    $upload = new UploadImg;
                    //executa o upload
                    $returning = $upload->uploadFiles($dir_base . "/", $input_xml_name, $_FILES [$input_xml_name], "x_m_l_invoice_" . trim(preg_replace('/[^0-9]/', '', $payment->getDateTime())));
                    //Obtém o através da classe Renurning e executa a ação
                    if ($returning->getValue() === 1) {
                        $payment->setInvoice_xml($returning->getDescription());
                        $filesEmail[1] = $dir_base . "/" . $returning->getDescription();
                    }
                }
            }
        }
        if (isset($file_invoice) && $file_invoice) {
            $filesEmail[0] = $dir_base . "/" . $file_invoice;
        }
        if (isset($file_xml) && $file_xml) {
            $filesEmail[1] = $dir_base . "/" . $file_xml;
        }
        //consuta se o envio de notificação para o cliente sobre o a NFSE ser enviado esta ativo
        $notification = new Notification;
        $notification = $notification->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "client_subscription_nfse"]]);
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
                //declara classe CronEmailNfse, classe responsável por gerenciar os e-mails enviados pelo sistema, para não haver sobrecarga do servidor
                $cron = new CronEmailNfse();
                //informa a qual pagamento pertence pelo GCID
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
                $cron->setSaveQuery();
            }
        }
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $payment->setSaveQuery();
        if ($return == 1) {
            echo "1->" . $translate->translate('NFS-e atualizada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 2) {
            //quando a nota é cadastrada é atribuido o status de emitida a variavel NFSe_Issued na classe de Pagamento da Assinatura 
            $signaturePaymentIssue = new SignaturePayment();
            $signaturePaymentIssue->setId($signaturePayment->getId());
            $signaturePaymentIssue->setNfse_issued(true);
            $signaturePaymentIssue->setSaveQuery();
            //menssagem de retorno
            echo "1->" . $translate->translate('NFS-e cadastrada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 3) {
            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}    