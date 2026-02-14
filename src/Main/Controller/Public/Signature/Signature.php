<?php

/**
 * Controller de Criação e Processamento de Assinatura (Multi-Gateway)
 * * Este script gerencia o ciclo de vida inicial de uma adesão:
 * 1. Segurança: Validação de sessão e prevenção de duplicidade.
 * 2. Dados: Recuperação de Cliente, Plano e aplicação de Cupons.
 * 3. Identidade: Geração de identificadores únicos (GCID).
 * 4. Persistência: Registro de Assinatura, Pagamento e Invoice.
 * 5. Integração: Comunicação com Gateways de Pagamento (EfiPay/Outros).
 * 6. Compliance: Registro de aceite de termos e renovação automática.
 * * @package Microfw
 * @author Jimmy Animes (YouTubeOS Project)
 */
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\EfiSignaturePaymentService;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller\EfiPaymentMessageHelper;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\SignaturePaymentInvoice;
use Microfw\Src\Main\Controller\Public\Signature\GetCoupon;
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Service\Public\Signatures\ManageSignatures;

// --------------------------------------------------------------------------
// INICIALIZAÇÃO E SEGURANÇA
// --------------------------------------------------------------------------

/** Validação de Sessão Protegida */
ProtectedPage::protectedPage();

/** Instância dos Componentes Core */
$translate = new Translate();
$config = new McClientConfig();
$service = new EfiSignaturePaymentService();
$paymentLog = new PaymentLogs();
$manageSignature = new ManageSignatures;

/**
 * CAPTURA DE ERROS CRÍTICOS (SHUTDOWN)
 * Monitora o encerramento do script para registrar erros fatais ou de sintaxe no log customizado.
 */
register_shutdown_function(function () use ($paymentLog) {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $paymentLog->saveCustomerPaymentLog([
            "category" => "PHP_CODE_ERROR",
            "message" => $error['message'],
            "file" => $error['file'],
            "line" => $error['line']
                ], $_SESSION['client_gcid'] ?? 'SESSAO_NAO_IDENTIFICADA');

        if (!headers_sent()) {
            echo "2->Ocorreu um erro interno de processamento. Por favor, contate o suporte.";
        }
    }
});

// --------------------------------------------------------------------------
// 1. TRATAMENTO E SANITIZAÇÃO DOS DADOS (POST)
// --------------------------------------------------------------------------

$payment_token = isset($_POST['payment_token']) ? trim($_POST['payment_token']) : '';
$installments = isset($_POST['installments']) ? intval($_POST['installments']) : 1;
$card_mask = isset($_POST['card_mask']) ? htmlspecialchars(trim($_POST['card_mask'])) : '';
$brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : 'efipay';
$method = isset($_POST['method']) ? trim($_POST['method']) : 'credit_card';
$cycle = isset($_POST['cycle']) ? trim($_POST['cycle']) : 'mensal';
$coupon_code = isset($_POST['coupon_code']) ? strtoupper(trim($_POST['coupon_code'])) : '';
$plan_id = isset($_POST['plan']) ? $_POST['plan'] : ""; // GCID do Plano

/** Validação de renovação automática e identificador de termos aceitos */
$auto_renew = (isset($_POST['auto_renew']) && $_POST['auto_renew'] == '1') ? 1 : 0;
$terms_id = (isset($_POST['terms']) && $_POST['terms'] != '0') ? $_POST['terms'] : 0;

/** Validação de campos obrigatórios iniciais */
if (empty($payment_token) || empty($plan_id)) {
    $paymentLog->saveCustomerPaymentLog("Erro: Dados incompletos no POST.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
    echo "2->" . $translate->translate('Dados incompletos. Por favor, recarregue a página e tente novamente.', $_SESSION['client_lang']);
    exit;
}

$client_id = (int) $_SESSION['client_id'];

// --------------------------------------------------------------------------
// 2. RECUPERAÇÃO DE ENTIDADES E VALIDAÇÕES DE NEGÓCIO
// --------------------------------------------------------------------------

/** @var Client $customer Instância do cliente logado */
$customer = new Client();
$customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $client_id]]);

/** Verifica se o cliente já possui uma assinatura ativa (Prevenção de duplicidade) */
$signatureActive = new Signature();
$signatureActive = $signatureActive->getQuery(single: true, customWhere: [
    ['column' => 'customer_id', 'value' => $client_id],
    ['column' => 'status', 'value' => 1]
        ]);

if ($signatureActive && $signatureActive->getId()) {
    $paymentLog->saveCustomerPaymentLog("Erro: Ja possui assinatura ativa.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
    echo "2->" . $translate->translate('Ja possui assinatura ativa. Por favor recarregue a página.', $_SESSION['client_lang']);
    exit;
}

$discount = null;
$coupon_id = null;
$accessPlan_id = null;
$accessPlan_price = null;
$gcid = "";

/** @var AccessPlan $accessPlan Recuperação do plano selecionado via GCID */
$accessPlan = new AccessPlan;
$accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $plan_id]]);

if ($accessPlan === null || $accessPlan->getId() === null) {
    echo "2->" . $translate->translate('Selecione um plano antes de prosseguir!', $_SESSION['client_lang']);
    exit;
} else {
    $accessPlan_id = $accessPlan->getId();
    $accessPlan_price = $accessPlan->getPrice();
}

if (empty($client_id) || empty($accessPlan_id)) {
    echo "2->" . $translate->translate('Dados incompletos. Por favor, recarregue a página e tente novamente.', $_SESSION['client_lang']);
    exit;
}

// --------------------------------------------------------------------------
// 3. PROCESSAMENTO DE CUPOM E GESTÃO DE IDENTIFICADORES (GCID)
// --------------------------------------------------------------------------

/** Validação e recuperação de desconto via cupom */
$getCoupon = new GetCoupon;
$getCoupon = $getCoupon->searchCoupon($coupon_code);

if ($getCoupon['status']) {
    $discount = $getCoupon['discount_percent'];
    $coupon_id = $getCoupon['code_coupon'];
}

$signature = new Signature;

/** Geração de GCID único para a nova Assinatura */
$gcid = $signature->getGenerateUniqueGcid(new Signature);
$signature->setGcid($gcid);

// --------------------------------------------------------------------------
// 4. CONFIGURAÇÃO E PERSISTÊNCIA DA ASSINATURA
// --------------------------------------------------------------------------

$signature->setCustomer_id($customer->getId());
$signature->setAccess_plan_id($accessPlan_id);
$signature->setCurrency_id(1);

if ($discount !== null && $coupon_id !== null) {
    $signature->setDiscount($discount);
    $signature->setAccess_plan_coupon_id($coupon_id);
}

$signature->setDate_start(date('Y-m-d H:i:s'));

if ($auto_renew === 1) {
    $signature->setAuto_renew($auto_renew);
    $signature->setAuto_renew_accepted_at(date('Y-m-d H:i:s'));
}

/** Cálculo de precificação e renovação baseada no ciclo (Mensal/Anual) */
$cyclePrice = $signature->calculateCycleDetails($accessPlan_price, $cycle);
$signature->setPrice($cyclePrice['price']);
$signature->setRenewal_cycle($cycle);
$signature->setStatus(1);

$returno = $signature->setSaveQuery();

// --------------------------------------------------------------------------
// 5. INFRAESTRUTURA DE ARQUIVOS E REGISTRO DE PAGAMENTO (FINANCEIRO)
// --------------------------------------------------------------------------

if ($returno === 2) {
    /** Recupera o objeto salvo para operações de diretório e financeiro */
    $signatureSearch = new Signature;
    $signatureSearch = $signatureSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gcid]]);

    /** Criação de diretórios físicos para armazenamento de faturas e invoices */
    $signatureSearch->prepareSignatureDirectory($_SESSION['client_gcid'], $gcid, $config);

    /** Registro da intenção de pagamento */
    $payment = new SignaturePayment;
    $gcid_payment = $payment->getGenerateUniqueGcid(new SignaturePayment);
    $payment->setGcid($gcid_payment);
    $payment->setSignature_id($signatureSearch->getId());
    $payment->setDate_billing(date('Y-m-d H:i:s'));
    $payment->setInstallment($installments);
    $payment->setDate_due(date('Y-m-d H:i:s', strtotime('+5 days')));
    $retorno_payment = $payment->setSaveQuery();

    // --------------------------------------------------------------------------
    // 6. INTEGRAÇÃO COM GATEWAY DE PAGAMENTO E PERSISTÊNCIA FINAL
    // --------------------------------------------------------------------------

    if ($retorno_payment === 2) {

        /** Validação de integridade do registro de pagamento específico */
        $signaturePayment = new SignaturePayment();
        $signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [
            ['column' => 'signature_id', 'value' => $signatureSearch->getId()],
            ['column' => 'gcid', 'value' => $gcid_payment]
        ]);

        if (!$signaturePayment) {
            echo "2->" . $translate->translate('Pagamento inválido.', $_SESSION['client_lang']);
            exit();
        }

        /** Geração da Invoice Relacional vinculada ao pagamento */
        $paymentInvoice = new SignaturePaymentInvoice;
        $paymentInvoice->setGcid($paymentInvoice->getGenerateUniqueGcid(new SignaturePaymentInvoice));
        $paymentInvoice->setSignature_payment_gcid($gcid_payment);
        $paymentInvoice->setSaveQuery();

        $discount_base = $signatureSearch->getDiscount() !== null ? (int) round($signatureSearch->getDiscount() * 100) : "";
        $final_amount = $cyclePrice['price'];

// 2. Só aplica a matemática se o desconto não for NULL e for maior que zero
        if ($discount !== null && $discount > 0) {
            $discount_val = $discount;
            $final_amount = $cyclePrice['price'] - ($cyclePrice['price'] * ($discount_val / 100));
        }

        try {
            $payload = [];
            $payment_config_id = 0;
            $treatReturn = null;
            $gatewayService = null;

            // Seleção dinâmica do Gateway e montagem do Payload específico
            switch ($type) {
                case 'efipay':
                    $gatewayService = new EfiSignaturePaymentService();
                    $payload = [
                        "type" => $type,
                        "method" => $method,
                        "notification_url" => $config->getDomain() . "/" . $config->getUrlPublic() . env("EFI_URL_NOTIFICATION"),
                        "payment" => $payment_token,
                        "product" => $accessPlan->getTitle(),
                        "price" => (int) round($final_amount * 100),
                        "discount" => $signatureSearch->getDiscount() ? (int) round($signatureSearch->getDiscount() * 100) : null,
                        "installments" => (int) $signaturePayment->getInstallment(),
                        "customer" => [
                            "gcid" => $customer->getGcid(),
                            "name" => $customer->getName(),
                            "cpf" => $customer->getCpf(),
                            "contact" => $customer->getContact(),
                            "email" => $customer->getEmail(),
                            "birth" => $customer->getBirth(),
                        ],
                    ];
                    $payment_config_id = env('EFI_DB_CODE');
                    $treatReturn = new EfiPaymentMessageHelper;
                    break;

                case 'mercadopago':
                    // $gatewayService = new MercadopagoPaymentService();
                    break;

                default:
                    echo "2->" . $translate->translate("Gateway de pagamento não suportado.", $_SESSION['client_lang']);
                    exit;
            }

            /** Processamento efetivo da transação no Gateway */
            $gatewayResult = $gatewayService->processPayment($payload);

            /** Tratamento de Erros retornados pelo Gateway ou Regras de Negócio do Service */
            if ($gatewayResult['status'] === "error") {
                $paymentLog->saveCustomerPaymentLog([
                    "category" => "GATEWAY_OR_BUSINESS_ERROR",
                    "result" => $gatewayResult,
                    "post_data" => $payload
                        ], $_SESSION['client_gcid']);

                echo "2->" . $translate->translate($gatewayResult['message'], $_SESSION['client_lang']);
                exit;
            }

            if (!$gatewayResult["allowed"]) {
                echo "2->" . $translate->translate($gatewayResult['message'], $_SESSION['client_lang']);
                exit();
            }

            // --------------------------------------------------------------------------
            // 5. ATUALIZAÇÃO DO STATUS E PERSISTÊNCIA (SUCESSO)
            // --------------------------------------------------------------------------
            /** UPDATE PAGAMENTO */
            $paymentload = [
                "id" => $signaturePayment->getId(),
                "gateway_status" => $gatewayResult['status'],
                "gateway_config_id" => $payment_config_id,
                "gateway_charge_id" => $gatewayResult['charge_id'],
                "gateway_payment_method" => $gatewayResult['paymentmethod'],
                "token" => $payment_token,
                "card_mask" => $card_mask,
                "installments" => $installments
            ];
            $manageSignature->setSignaturePayment($paymentload);

            /** Atualização da Assinatura e Registro de Compliance (Aceite de Termos) */
            if (!empty($auto_renew) && !empty($terms_id)) {
                $manageSignature->setAutoRenewHistory($terms_id, $signatureSearch->getId());
            }

            /** Retorno final amigável para o cliente (UX) */
            echo $treatReturn::translateStatus($gatewayResult['status'], $_SESSION['client_lang']);
        } catch (\Exception $e) {
            /** Registro de exceções técnicas durante a execução do Gateway */
            $paymentLog->saveCustomerPaymentLog([
                "category" => "SERVICE_EXCEPTION",
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
                    ], $_SESSION['client_gcid']);

            error_log("Payment error: " . $e->getMessage());
            echo "2->" . $translate->translate("Ocorreu um erro ao processar seu pagamento. Por favor, tente novamente.", $_SESSION['client_lang']);
        }
    } else {
        $paymentLog->saveCustomerPaymentLog("Erro: Falha ao salvar SignaturePayment (retorno_payment != 2)", $_SESSION['client_gcid']);
        echo "2->" . $translate->translate('Erro ao registrar pagamento. Tente novamente.', $_SESSION['client_lang']);
        exit;
    }
} else {
    $paymentLog->saveCustomerPaymentLog("Erro: Falha ao salvar Signature (returno != 2)", $_SESSION['client_gcid']);
    echo "2->" . $translate->translate('Erro ao processar assinatura. Tente novamente.', $_SESSION['client_lang']);
    exit;
}