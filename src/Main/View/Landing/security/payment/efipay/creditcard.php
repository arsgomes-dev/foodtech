<?php

/**
 * Script de Processamento de Pagamento de Assinatura via Cartão de Crédito
 * * Este script gerencia o ciclo de vida inicial de uma assinatura:
 * 1. Validação de segurança e sessão.
 * 2. Captura e tratamento de inputs do frontend.
 * 3. Cálculos de descontos e renovação.
 * 4. Persistência de registros (Signature e SignaturePayment).
 * 6. Registro de logs de auditoria e compliance.
 * * @package Microfw\Controller\Landing
 * @version 1.1
 */
session_start();

use Microfw\Src\Main\Controller\Landing\Login\ProtectedPage;
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\SignaturePaymentInvoice;
use Microfw\Src\Main\Controller\Landing\Controller\GetCoupon;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\EfiSignaturePaymentService;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller\EfiPaymentMessageHelper;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs;
use Microfw\Src\Main\Common\Service\Public\Signatures\ManageSignatures;

// ==========================================================================
// CONFIGURAÇÕES INICIAIS E SEGURANÇA
// ==========================================================================

/** Validação de Sessão Protegida */
ProtectedPage::protectedPage();

header('Content-Type: application/json');

/** @var PaymentLogs Instância para log de transações e erros */
$paymentLog = new PaymentLogs;
$manageSignature = new ManageSignatures;

/**
 * HANDLER DE ERROS FATAIS
 * Garante que erros de execução (Runtime) sejam logados antes do encerramento do script.
 */
register_shutdown_function(function () use ($paymentLog) {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $paymentLog->saveCustomerPaymentLog([
            "category" => "PHP_FATAL_ERROR",
            "message" => $error['message'],
            "file" => $error['file'],
            "line" => $error['line']
                ], $_SESSION['client_gcid'] ?? 'SYSTEM_CRASH');

        if (!headers_sent()) {
            echo "2->Ocorreu um erro interno de processamento. Por favor, contate o suporte.";
        }
    }
});

/** @var McClientConfig Configurações globais do sistema */
$config = new McClientConfig();

/** @var Translate Componente de internacionalização */
$translate = new Translate();

// ==========================================================================
// 1. CAPTURA E HIGIENIZAÇÃO DE DADOS (INPUTS)
// ==========================================================================

$payment_token = isset($_POST['payment_token']) ? trim($_POST['payment_token']) : '';
$installments = isset($_POST['installments']) ? intval($_POST['installments']) : 1;
$card_mask = isset($_POST['card_mask']) ? htmlspecialchars(trim($_POST['card_mask'])) : '';
$brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : 'efipay';
$method = isset($_POST['method']) ? trim($_POST['method']) : 'credit_card';
$plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : "";
$cycle = isset($_POST['cycle']) ? trim($_POST['cycle']) : 'mensal';
$coupon_code = isset($_POST['coupon_code']) ? strtoupper(trim($_POST['coupon_code'])) : '';
$auto_renew = (isset($_POST['auto_renew']) && $_POST['auto_renew'] == '1') ? 1 : 0;
$terms_id = (isset($_POST['terms']) && $_POST['terms'] !== '0') ? $_POST['terms'] : 0;

/** Validação de obrigatoriedade de campos críticos */
if (empty($payment_token) || empty($plan_id)) {
    $paymentLog->saveCustomerPaymentLog("Erro: Dados incompletos no POST.", $_SESSION['client_gcid'] ?? 'SESSAO_INVALIDA');
    echo "2->" . $translate->translate('Dados incompletos. Por favor, recarregue a página e tente novamente.', $_SESSION['client_lang']);
    exit;
}

$client_id = (int) $_SESSION['client_id'];

// ==========================================================================
// 2. RECUPERAÇÃO DE ENTIDADES (CLIENTE E PLANO)
// ==========================================================================

/** @var Client Busca dados do cliente logado */
$customer = new Client();
$customer = $customer->getQuery(single: true, customWhere: [
    ['column' => 'id', 'value' => $client_id]
        ]);

$discount = null;
$coupon_id = null;
$accessPlan_id = null;
$accessPlan_price = null;
$gcid = "";

/** @var AccessPlan Validação da existência do plano selecionado */
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

// ==========================================================================
// 3. PROCESSAMENTO DE CUPOM E GERENCIAMENTO DE GCID
// ==========================================================================

/** Verificação de validade de cupom de desconto */
$getCoupon = new GetCoupon;
$getCoupon = $getCoupon->searchCoupon($coupon_code);

if ($getCoupon['status']) {
    $discount = $getCoupon['discount_percent'];
    $coupon_id = $getCoupon['code_coupon'];
}

/** @var Signature Preparação da entidade de assinatura */
$signature = new Signature;
$gcid = $signature->getGenerateUniqueGcid(new Signature);
$signature->setGcid($gcid);

// ==========================================================================
// 4. CONFIGURAÇÃO E SALVAMENTO DA ASSINATURA
// ==========================================================================

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

/** Cálculo de preço com base no ciclo (mensal/anual) */
$cyclePrice = $signature->calculateCycleDetails($accessPlan_price, $cycle);
$signature->setPrice($cyclePrice['price']);
$signature->setStatus(1);

$returno = $signature->setSaveQuery();

// ==========================================================================
// 5. CRIAÇÃO DE DIRETÓRIOS E REGISTRO DE PAGAMENTO
// ==========================================================================

if ($returno === 2) {
    /** Recupera a assinatura recém-criada */
    $signatureSearch = new Signature;
    $signatureSearch = $signatureSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gcid]]);

    /** Cria estrutura de pastas para documentos da assinatura */
    $signatureSearch->prepareSignatureDirectory($_SESSION['client_gcid'], $gcid, $config);

    /** @var SignaturePayment Criação da intenção de pagamento */
    $payment = new SignaturePayment;
    $gcid_payment = $payment->getGenerateUniqueGcid(new SignaturePayment);
    $payment->setGcid($gcid_payment);
    $payment->setSignature_id($signatureSearch->getId());
    $payment->setDate_billing(date('Y-m-d H:i:s'));
    $payment->setPayment_config_id(env('EFI_DB_CODE'));
    $payment->setInstallment($installments);
    $payment->setDate_due(date('Y-m-d H:i:s', strtotime('+5 days')));
    $retorno_payment = $payment->setSaveQuery();

    // ==========================================================================
    // 6. PROCESSAMENTO DO GATEWAY DE PAGAMENTO (SERVICE)
    // ==========================================================================

    if ($retorno_payment === 2) {
        /** Geração de Invoice Relacional */
        $paymentInvoice = new SignaturePaymentInvoice;
        $paymentInvoice->setGcid($paymentInvoice->getGenerateUniqueGcid(new SignaturePaymentInvoice));
        $paymentInvoice->setSignature_payment_gcid($gcid_payment);
        $paymentInvoice->setSaveQuery();

        /** Cálculo do montante final com aplicação de desconto, se houver */
        $final_amount = $cyclePrice['price'];
        if ($discount !== null && $discount > 0) {
            $discount_val = $discount;
            $final_amount = $cyclePrice['price'] - ($cyclePrice['price'] * ($discount_val / 100));
        }

        try {
            $payload = [];
            $payment_config_id = 0;
            $treatReturn = null;
            $gatewayService = null;

            /** SELEÇÃO DINÂMICA DO GATEWAY */
            switch ($type) {
                case 'efipay':
                    $gatewayService = new EfiSignaturePaymentService();
                    $payload = [
                        "type" => $type,
                        "method" => $method,
                        "notification_url" => $config->getDomain() . "/" . $config->getUrlPublic() . env("EFI_URL_NOTIFICATION"),
                        "payment" => $payment_token,
                        "product" => $accessPlan->getTitle(),
                        "price" => (int) round($final_amount * 100), // Valor em centavos
                        "discount" => $signatureSearch->getDiscount() ? (int) round($signatureSearch->getDiscount() * 100) : null,
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
                    exit;
            }

            /** Comunicação com a API do Gateway */
            $gatewayResult = $gatewayService->processPayment($payload);

            /** Tratamento de Erros de Regra de Negócio/Gateway */
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

            // ==========================================================================
            // 7. ATUALIZAÇÃO DO STATUS E PERSISTÊNCIA (SUCESSO)
            // ==========================================================================
            /** Atualiza o registro de pagamento com os dados reais retornados pela API */
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

            /** Registro de Auditoria e Histórico de Aceite de Termos (Compliance) */
            if (!empty($auto_renew) && !empty($terms_id)) {
                $manageSignature->setAutoRenewHistory($terms_id, $signatureSearch->getId());
            }

            /** Resposta final ao frontend */
            echo $treatReturn::translateStatus($gatewayResult['status'], $_SESSION['client_lang']);
        } catch (\Exception $e) {
            /** Log de exceções inesperadas durante o processamento do service */
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