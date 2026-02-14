<?php

/**
 * Controller de Processamento de Pagamento (Cartão de Crédito - Versão Estendida)
 * * Este script gerencia o ciclo completo de pagamento: validação de sessão, 
 * criação de diretórios de cobrança, geração de registros de pagamento/fatura, 
 * aplicação de cupons e integração com o gateway EfiPay.
 * * @package Microfw
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

/** Instância dos Componentes de Serviço e Configuração */
$translate = new Translate();
$config = new McClientConfig();
$service = new EfiSignaturePaymentService();
$paymentLog = new PaymentLogs();
$manageSignature = new ManageSignatures;

/**
 * CAPTURA DE ERROS CRÍTICOS (SHUTDOWN)
 * Monitora erros fatais de PHP para registro em log antes da interrupção do script.
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
// 1. TRATAMENTO E SANITIZAÇÃO DE DADOS (POST)
// --------------------------------------------------------------------------

$payment_token = isset($_POST['payment_token']) ? trim($_POST['payment_token']) : '';
$installments = isset($_POST['installments']) ? intval($_POST['installments']) : 1;
$card_mask = isset($_POST['card_mask']) ? htmlspecialchars(trim($_POST['card_mask'])) : '';
$brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : 'efipay';
$method = isset($_POST['method']) ? trim($_POST['method']) : 'credit_card';
$signature = isset($_POST['signature']) ? trim($_POST['signature']) : "";
$signaturePaymentGcid = isset($_POST['signaturePayment']) ? trim($_POST['signaturePayment']) : "";
$cycle = isset($_POST['cycle']) ? trim($_POST['cycle']) : 'mensal';
$coupon_code = isset($_POST['coupon_code']) ? strtoupper(trim($_POST['coupon_code'])) : '';

/** Validação de renovação automática e aceite de termos */
$auto_renew = (isset($_POST['auto_renew']) && $_POST['auto_renew'] == '1') ? 1 : 0;
$terms_id = (isset($_POST['terms']) && $_POST['terms'] !== '0') ? $_POST['terms'] : 0;

/** Busca de Dados do Cliente */
$customer = new Client();
$customer = $customer->getQuery(single: true, customWhere: [
    ['column' => 'id', 'value' => $_SESSION['client_id']]
        ]);

// --------------------------------------------------------------------------
// 2. VALIDAÇÃO DE OBRIGATORIEDADE E BUSCA DE ASSINATURA
// --------------------------------------------------------------------------

if (empty($payment_token) || empty($signature)) {
    $paymentLog->saveCustomerPaymentLog("Erro: POST com dados incompletos.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
    echo "2->" . $translate->translate('Dados de pagamento insuficientes. Recarregue a página.', $_SESSION['client_lang']);
    exit;
}

$signatureSearch = new Signature;
$signatureSearch = $signatureSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $signature]]);

if (!$signatureSearch) {
    $paymentLog->saveCustomerPaymentLog("Erro: Assinatura não encontrada.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
    echo "2->" . $translate->translate('Assinatura inválida. Recarregue a página.', $_SESSION['client_lang']);
    exit;
}

/** Verifica se já existe cobrança ativa para evitar duplicidade */
// Previne duplicidade de cobrança ativa
$existingPayment = (new SignaturePayment())->getQuery(
        single: true,
        customWhere: [['column' => 'signature_id', 'value' => $signatureSearch->getId()]],
        whereNot: ["date_payment" => null, "payment_status" => null, "payment_config_id" => null],
        customWhereOr: [['column' => 'payment_status', 'values' => ['new', 'waiting', 'identified', 'approved', 'paid']]]
);

if ($existingPayment) {
    echo "2->" . $translate->translate('Já existe uma cobrança em processamento para esta assinatura.', $_SESSION['client_lang']);
    exit();
}

// --------------------------------------------------------------------------
// 3. CRIAÇÃO DE REGISTRO DE PAGAMENTO E AMBIENTE DE ARQUIVOS
// --------------------------------------------------------------------------

if (empty($signaturePaymentGcid)) {
    /** Criação de diretórios para armazenamento de faturas */
    $signatureSearch->prepareSignatureDirectory($_SESSION['client_gcid'], $signature, $config);

    $payment = new SignaturePayment;
    $gcid_payment = $payment->getGenerateUniqueGcid(new SignaturePayment);
    $payment->setGcid($gcid_payment);
    $payment->setSignature_id($signatureSearch->getId());
    $payment->setDate_billing(date('Y-m-d H:i:s'));
    $payment->setPayment_config_id(env('EFI_DB_CODE'));
    $payment->setInstallment($installments);
    $payment->setDate_due(date('Y-m-d H:i:s', strtotime('+5 days')));
    $signaturePaymentGcid = $gcid_payment;

    $retorno_payment = $payment->setSaveQuery();

    if ($retorno_payment === 2) {
        /** Geração da Invoice Relacional */
        $paymentInvoice = new SignaturePaymentInvoice;
        $paymentInvoice->setGcid($paymentInvoice->getGenerateUniqueGcid(new SignaturePaymentInvoice));
        $paymentInvoice->setSignature_payment_gcid($gcid_payment);
        $paymentInvoice->setSaveQuery();
    } else {
        $paymentLog->saveCustomerPaymentLog("Erro: Cadastrar pagamento.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
        echo "2->" . $translate->translate('Ocorreu um erro ao realizar o pagamento. Recarregue a página.', $_SESSION['client_lang']);
        exit;
    }
}

/** Recuperação da Entidade de Pagamento */
$signaturePayment = new SignaturePayment();
$signaturePayment = $signaturePayment->getQuery(single: true, customWhere: [
    ['column' => 'signature_id', 'value' => $signatureSearch->getId()],
    ['column' => 'gcid', 'value' => $signaturePaymentGcid]
        ]);

if (!$signaturePayment) {
    echo "2->" . $translate->translate('Pagamento inválido.', $_SESSION['client_lang']);
    exit();
}

/** Recuperação do Plano de Acesso */
$accessPlan_price = null;
$accessPlan_id = null;
$accessPlan = new AccessPlan;
$accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signatureSearch->getAccess_plan_id()]]);

if ($accessPlan === null || $accessPlan->getId() === null) {
    echo "2->" . $translate->translate('Selecione um plano antes de prosseguir!', $_SESSION['client_lang']);
    exit;
} else {
    $accessPlan_id = $accessPlan->getId();
    $accessPlan_price = $accessPlan->getPrice();
}

// --------------------------------------------------------------------------
// 4. PROCESSAMENTO DE PLANO DE ACESSO E CUPOM
// --------------------------------------------------------------------------

$signatureUpdate = new Signature();
$signatureUpdate->setId($signatureSearch->getId());

$signature_price_base = 0.00;
if ($signatureSearch->getRenewal_cycle() === "anual") {
    $signature_price_base = (float) $signatureSearch->getPrice() / env('PAG_CYCLE_ANUAL_X_PRICE');
} else {
    $signature_price_base = (float) $signatureSearch->getPrice();
}

$discount = $signatureSearch->getDiscount() !== null ? (int) round($signatureSearch->getDiscount() * 100) : "";
$discount_percentagem = (float) $signatureSearch->getDiscount();
/** Processamento de Cupom de Desconto */
$couponResult;
if (!empty($coupon_code)) {
    $getCoupon = new GetCoupon;
    $couponResult = $getCoupon->searchCoupon($coupon_code);

    $signatureCoupon = new Signature;
    $signatureCoupon->setId($signatureSearch->getId());

    if ($couponResult['status']) {
        $signatureCoupon->setDiscount($couponResult['discount_percent']);
        $signatureCoupon->setAccess_plan_coupon_id($couponResult['code_coupon']);
        $discount = $couponResult['discount_percent'] !== null ? (int) round($couponResult['discount_percent'] * 100) : "";
        $discount_percentagem = (float) $couponResult['discount_percent'];
    }
    $returnoCoupon = $signatureCoupon->setSaveQuery();
    if ($returnoCoupon !== 1) {
        $paymentLog->saveCustomerPaymentLog("Erro: Atualizar cupom na assinatura.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
        echo "2->" . $translate->translate('Ocorreu um erro ao adicionar cupom de desconto. Recarregue a página e tente novamente.', $_SESSION['client_lang']);
        exit;
    }
}

$cyclePrice = $signatureSearch->calculateCycleDetails($signature_price_base, $cycle);
$final_amount = $cyclePrice['price'];

// 2. Só aplica a matemática se o desconto não for NULL e for maior que zero
if ($discount_percentagem !== null && $discount_percentagem > 0) {
    $discount_val = $discount_percentagem;
    $final_amount = $cyclePrice['price'] - ($cyclePrice['price'] * ($discount_val / 100));
}
// --------------------------------------------------------------------------
// 5. EXECUÇÃO DO GATEWAY DE PAGAMENTO
// --------------------------------------------------------------------------

try {
    $payload = [];
    $payment_config_id = 0;
    $treatReturn = null;

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
                "discount" => $discount ?? null,
                "installments" => (int) $installments,
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
            exit();
    }

    /** Processamento junto ao Service */
    $gatewayResult = $gatewayService->processPayment($payload);

    /** Tratamento de Erro de Negócio ou Gateway */
    if ($gatewayResult['status'] === "error") {
        $paymentLog->saveCustomerPaymentLog([
            "category" => "GATEWAY_OR_BUSINESS_ERROR",
            "result" => $gatewayResult,
            "post_data" => $payload
                ], $_SESSION['client_gcid']);

        echo "2->" . $translate->translate($gatewayResult['message'], $_SESSION['client_lang']);
        exit();
    } else {

        if (!$gatewayResult["allowed"]) {
            echo "2->" . $translate->translate($gatewayResult['message'], $_SESSION['client_lang']);
            exit();
        }

        // --------------------------------------------------------------------------
        // 6. SUCESSO: ATUALIZAÇÃO DE STATUS E PERSISTÊNCIA
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

        /** UPDATE ASSINATURA */
        $signatureUpdate->setRenewal_cycle($cycle);
        $cyclePrice = $signatureSearch->calculateCycleDetails($accessPlan_price, $cycle);

        /** ACEITE DE TERMO E RENOVAÇÃO AUTOMÁTICA */
        if (!empty($auto_renew) && !empty($terms_id)) {
            $signatureUpdate->setAuto_renew(1);
            $signatureUpdate->setAuto_renew_accepted_at(date('Y-m-d H:i:s'));
            //Quando tiver auto renovaçao ativa persite no historico no banco de dados juntamente com o termo
            $manageSignature->setAutoRenewHistory($terms_id, $signatureSearch->getId());
        } else {
            $signatureUpdate->setAuto_renew(0);
            $signatureUpdate->setAuto_renew_accepted_at(null);
        }
        $signatureUpdate->setPrice($cyclePrice['price']);
        $signatureUpdate->setSaveQuery();

        /** Tradução final do status para o front-end */
        echo $treatReturn::translateStatus($gatewayResult['status'], $_SESSION['client_lang']);
    }
} catch (\Exception $e) {
    /** Registro de Logs de Exceção para Debug */
    $paymentLog->saveCustomerPaymentLog([
        "category" => "EXCEPTION_ERROR",
        "message" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "client" => $_SESSION['client_id']
            ], $_SESSION['client_gcid']);

    error_log("Critical Payment Error: " . $e->getMessage());
    echo "2->" . $translate->translate("Não foi possível processar seu pagamento. Tente novamente mais tarde.", $_SESSION['client_lang']);
}