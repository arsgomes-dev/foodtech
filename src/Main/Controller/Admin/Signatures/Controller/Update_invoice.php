<?php

session_start();

//Função para insere/atualiza a NFSe
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePaymentInvoice;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Currency;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa as configurações do SITE
$config = new McConfig();
$stConfig = new StConfig();
$st = new StConfig;
$st = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
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

        //declara a classe para consultar o pagamento da assinatura (SignaturePayment) e informa que será consultado pelo GCID
        $signaturePayment = new SignaturePayment();
        $signaturePayment->setTable_db_primaryKey("gcid");
        $signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);
        $gcid = $signaturePayment->getGcid();
        //quando a nota é cadastrada é atribuido o status de emitida a variavel NFSe_Issued na classe de Pagamento da Assinatura 
        $signaturePaymentIssue = new SignaturePayment();
        $signaturePaymentIssue->setId($signaturePayment->getId());
        //declara a classe para consultar a assinatura (Signature) pelo ID salvo na tabela SignaturePayment
        $signature = new Signature;
        $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getSignature_id()]]);
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

        //declara as variaveis para verificar os aquivos da NFSE PDF e XML
        $arq_invoice = false;
        $arq_xml = false;
        //declara variável para inserir/atualizar NFSe no DB
        $paymentInvoice = new SignaturePaymentInvoice();
        if (!empty($_POST['invoice']) && isset($_POST['invoice'])) {
            //caso a NFS-e ja exista é atualizado e informa que será atualizado pelo GCID
            $paymentInvoice->setGcid($_POST['invoice']);
            $paymentInvoice->setUser_id_updated($_SESSION['user_id']);

            //consulta para verificar se já existe NFS-e salva = new SignaturePaymentInvoice();
            $paymentInvoiceSearch = new SignaturePaymentInvoice();
            $paymentInvoiceSearch->setTable_db_primaryKey("gcid");
            $paymentInvoiceSearch = $paymentInvoiceSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['invoice']]]);
            $paymentInvoice->setId($paymentInvoiceSearch->getId());
            $arq_invoice = ($paymentInvoiceSearch->getInvoice_pdf() !== "" && $paymentInvoiceSearch->getInvoice_pdf() !== null) ? true : false;
            $arq_xml = ($paymentInvoiceSearch->getInvoice_xml() !== "" && $paymentInvoiceSearch->getInvoice_xml() !== null) ? true : false;
        } else {
            //insere um novo registro
            $paymentInvoice->setGcid();
            $paymentInvoice->setSignature_payment_gcid($_POST['code']);
            $paymentInvoice->setUser_id_created($_SESSION['user_id']);
            $signaturePaymentIssue->setNfse_issued(true);
        }

        //formata os preços pela moeda utilizado pelo usuário
        $price_total = $translate->translateMonetaryDoubleLocale($_POST['total'], $_SESSION['user_currency_locale']);
        $price_net = $translate->translateMonetaryDoubleLocale($_POST['net'], $_SESSION['user_currency_locale']);
        //insere informações a serem inseridas/atualizadas no DB
        $paymentInvoice->setTotal_amount($price_total);
        $paymentInvoice->setNet_amount($price_net);
        $paymentInvoice->setNumber_invoice($_POST['number']);
        $paymentInvoice->setSeries_invoice($_POST['serie']);
        $paymentInvoice->setVerification_code($_POST['verification']);
        $paymentInvoice->setDate_issue($_POST['issue']);
        $paymentInvoice->setConsultation_url($_POST['url']);
        //Verifica se as variáveis relacionadas à existência de cancelamento estão devidamente preenchidas.
        if (!empty($_POST['canceled']) && isset($_POST['canceled'])) {
            $paymentInvoice->setCanceled_at("canceled");
        }
        if (!empty($_POST['cancellation']) && isset($_POST['cancellation'])) {
            $paymentInvoice->setCancel_reason($_POST['cancellation']);
        }
        //verifica se existe os arquivos PDF E XML
        if (!empty(array_filter($_FILES)) && $_FILES) {
            $returning = new Returning;
            //define os nomes das variáveis
            $input_invoice_name = "invoice_arq";
            $input_xml_name = "xml_arq";
            //define o diretorio dos arquivos
            $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $gcid;
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices")) {
                if (!file_exists($dir_base)) {
                    mkdir($dir_base, 0777);
                }
            }
            //verifica se existe a variável PDF seta na classe
            if (isset($_FILES[$input_invoice_name])) {
                if ($_FILES [$input_invoice_name]['name'] && $arq_invoice === false && $gcid !== "" && $gcid !== null) {
                    $returning = new Returning;
                    $upload = new UploadImg;
                    $returning = $upload->uploadFiles($dir_base . "/", $input_invoice_name, $_FILES [$input_invoice_name], "invoice_" . trim(preg_replace('/[^0-9]/', '', $paymentInvoice->getDateTime())));
                    if ($returning->getValue() === 1) {
                        $paymentInvoice->setInvoice_pdf($returning->getDescription());
                    }
                }
            }
            //verifica se existe a variável XML seta na classe
            if (isset($_FILES[$input_xml_name])) {
                if ($_FILES [$input_xml_name]['name'] !== null && $arq_xml === false && $gcid !== "" && $gcid !== null) {
                    $returning = new Returning;
                    $upload = new UploadImg;
                    $returning = $upload->uploadFiles($dir_base . "/", $input_xml_name, $_FILES [$input_xml_name], "x_m_l_invoice_" . trim(preg_replace('/[^0-9]/', '', $paymentInvoice->getDateTime())));
                    if ($returning->getValue() === 1) {
                        $paymentInvoice->setInvoice_xml($returning->getDescription());
                    }
                }
            }
        }
        //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $paymentInvoice->setSaveQuery();
        if ($return == 1) {
            echo "1->" . $translate->translate('NFS-e atualizada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 2) {
            $signaturePaymentIssue->setSave();
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