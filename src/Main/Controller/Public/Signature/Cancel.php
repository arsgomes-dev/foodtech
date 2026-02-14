<?php

/**
 * Controller de Cancelamento de Assinatura
 * Gerencia o cancelamento e estorno automático (Regra de 7 dias)
 */

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\EfiSignaturePaymentService;
use Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs;

// Proteção da página
ProtectedPage::protectedPage();

header('Content-Type: application/json');

$translate = new Translate();
$paymentLog = new PaymentLogs();
$paymentService = new EfiSignaturePaymentService();

/**
 * CAPTURA DE ERROS FATAIS
 */
register_shutdown_function(function() use ($paymentLog) {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $paymentLog->saveCustomerPaymentLog([
            "category" => "CANCEL_FATAL_ERROR",
            "message"  => $error['message'],
            "file"     => $error['file'],
            "line"     => $error['line']
        ], $_SESSION['client_gcid'] ?? 'SYSTEM_CRASH');
    }
});

// Validação dos dados de entrada
if (empty($_POST['signature']) || empty($_SESSION['client_id'])) {
    echo "2->" . $translate->translate('Selecione a assinatura que deseja cancelar.', $_SESSION['client_lang']);
    exit;
}

$signatureGcid = $_POST['signature'];
$customerId = (int) $_SESSION['client_id'];

try {
    // 1. Busca os dados da assinatura
    $signatureEntity = new Signature();
    $signature = $signatureEntity->getQuery(single: true, customWhere: [
        ['column' => 'customer_id', 'value' => $customerId], 
        ['column' => 'gcid', 'value' => $signatureGcid]
    ]);

    if (!$signature) {
        echo "2->" . $translate->translate('Assinatura não encontrada.', $_SESSION['client_lang']);
        exit;
    }

    // 2. Busca o último pagamento para verificar o método (credit_card / efipay)
    $paymentEntity = new SignaturePayment();
    $lastPayment = $paymentEntity->getQuery(single: true, customWhere: [
        ['column' => 'signature_id', 'value' => $signature->getId()],
        ['column' => 'payment_status', 'value' => 'paid']
    ], order: "created_at DESC");

    $eligibleForRefund = false;
    if ($lastPayment) {
        // Verifica se o método é cartão de crédito e o tipo é efipay (ou o config_id da Efí)
        $isCreditCard = ($lastPayment->getPayment_method() === 'credit_card');
        $isEfiPay = ($lastPayment->getPayment_config_id() == env('EFI_DB_CODE'));

        if ($isCreditCard && $isEfiPay) {
            $eligibleForRefund = true;
        }
    }

    // 3. Lógica de Cancelamento vs Estorno
    if ($eligibleForRefund) {
        // Tenta o estorno pela regra de 7 dias
        $refundResult = $paymentService->refundByRepentanceRule($signatureGcid);

        if ($refundResult['allowed']) {
            // Se o estorno foi permitido e processado
            $signatureCancel = new Signature();
            $signatureCancel->setId($signature->getId());
            $signatureCancel->setStatus(3); // Status de Estornada/Cancelada Total
            $signatureCancel->setDate_end(date('Y-m-d H:i:s'));
            $signatureCancel->setSaveQuery();

            echo "1->" . $translate->translate('Sua assinatura foi cancelada e o valor foi estornado com sucesso!', $_SESSION['client_lang']);
            exit;
        }
    }

    // 4. Caso não seja elegível para estorno ou tenha passado de 7 dias: Cancelamento Padrão
    $signatureCancel = new Signature();
    $signatureCancel->setId($signature->getId());
    $signatureCancel->setStatus(2); // Cancelada (aguardando fim do ciclo)
    $signatureCancel->setDate_end($signature->getDate_renovation());
    $return = $signatureCancel->setSaveQuery();

    if ($return === 2) {
        echo "1->" . $translate->translate('Sua assinatura foi cancelada, mas você poderá usar o serviço até', $_SESSION['client_lang']) . ": " . $signature->getDate_renovation();
    } else {
        echo "2->" . $translate->translate('Erro ao cancelar assinatura, tente novamente.', $_SESSION['client_lang']);
    }

} catch (\Exception $e) {
    $paymentLog->saveCustomerPaymentLog([
        "category" => "CANCEL_EXCEPTION",
        "message"  => $e->getMessage(),
        "signature_gcid" => $signatureGcid
    ], $_SESSION['client_gcid']);

    echo "2->" . $translate->translate('Ocorreu um erro ao processar o cancelamento. Contate o suporte.', $_SESSION['client_lang']);
}