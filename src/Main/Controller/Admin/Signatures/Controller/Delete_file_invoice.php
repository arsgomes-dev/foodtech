<?php

session_start();

//Função para excluir NFSe do cliente
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePaymentInvoice;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa as configurações do SITE
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("customer_signatures", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['type']) && isset($_POST['type'])) {


        //declara variável e consulta pelo GCID para compor o diretorio do arquivo
        $signatureInvoice = new SignaturePaymentInvoice();
        $signatureInvoiceUpdate = new SignaturePaymentInvoice();
        $signatureInvoice->setTable_db_primaryKey("gcid");
        $signatureInvoice = $signatureInvoice->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);

        $signaturePayment = new SignaturePayment();
        $signaturePayment->setTable_db_primaryKey("gcid");
        $signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $signatureInvoice->getSignature_payment_gcid()]]);

        $signature = new Signature;
        $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getSignature_id()]]);

        $customer = new Customers;
        $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);

        //diretorio base
        $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $signaturePayment->getGcid() . "/";
        //verifica se a informação do arquivo PDF existe no banco e se o tipo de arquivo a ser excuido é o PDF 
        if ($signatureInvoice->getInvoice_pdf() !== "" && $signatureInvoice->getInvoice_pdf() !== null && $type === "pdf") {
            //setar variável com a descrição do arquivo
            $file = $signatureInvoice->getInvoice_pdf();
            //seta a classe de retorno que mostra o resultado da exclusão
            $returning = new Returning;
            //seta a classe que efetua as ações com arquivos
            $upload = new UploadImg;
            //executa a ação de exclusão e recebe um retorno
            $returning = $upload->delete($dir_base, $file);
            if ($returning->getValue() === 1) {
                //caso o retorno da exclusão seja positivo seta o valor em branco na variável do DB
                $signatureInvoiceUpdate->setId($signatureInvoice->getId());
                $signatureInvoiceUpdate->setInvoice_pdf("");
                $signatureInvoiceUpdate->setSaveQuery();
                echo "1->" . $returning->getDescription();
            } else {
                echo "2->" . $returning->getDescription();
            }
            //verifica se a informação do arquivo XML existe no banco e se o tipo de arquivo a ser excuido é o XML 
        } else if ($signatureInvoice->getInvoice_xml() !== "" && $signatureInvoice->getInvoice_xml() !== null && $type === "xml") {
            //setar variável com a descrição do arquivo
            $file = $signatureInvoice->getInvoice_xml();
            //seta a classe de retorno que mostra o resultado da exclusão
            $returning = new Returning;
            //seta a classe que efetua as ações com arquivos
            $upload = new UploadImg;
            //executa a ação de exclusão e recebe um retorno
            $returning = $upload->delete($dir_base, $file);
            if ($returning->getValue() === 1) {
                //caso o retorno da exclusão seja positivo seta o valor em branco na variável do DB
                $signatureInvoiceUpdate->setId($signatureInvoice->getId());
                $signatureInvoiceUpdate->setInvoice_xml("");
                $signatureInvoiceUpdate->setSaveQuery();
                echo "1->" . $returning->getDescription();
            } else {
                echo "2->" . $returning->getDescription();
            }
        } else {
            echo "2->" . $translate->translate('Nenhum arquivo localizado para exclusão!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}    