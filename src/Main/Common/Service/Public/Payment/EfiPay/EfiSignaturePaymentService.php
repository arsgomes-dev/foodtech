<?php

namespace Microfw\Src\Main\Common\Service\Public\Payment\EfiPay;

use Microfw\Src\Main\Common\Entity\Public\{
    Signature,
    SignaturePayment,
    SignaturePaymentHistory,
    PaymentStatus,
    Client,
    McClientConfig
};
use Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller\EfiPaymentMessageHelper;
use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller\EfiPayment;
use Exception;

/**
 * Service responsável pela lógica de negócio de pagamentos de assinaturas.
 */
/**
 * CAPTURA DE ERROS CRÍTICOS (SHUTDOWN)
 * Monitora erros fatais de PHP para registro em log antes da interrupção do script.
 */
$paymentLog = new PaymentLogs();
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

class EfiSignaturePaymentService {

    /**
     * Processa o pagamento via cartão de crédito integrando com o gateway EFI.
     * * @param int $customerId ID do cliente
     * @param string $signatureGcid GCID da assinatura
     * @param string $signaturePaymentGcid GCID do registro de pagamento
     * @param array $post Dados do formulário (token, método, termos, etc)
     * @return array Status e mensagem do processamento
     */
    public function processPayment(array $payload): array {

        switch ($payload['method']) {
            case 'credit_card':
                return $this->executeCreditCard($payload);
            case 'pix':
                return $this->executePix($payload);
            default:
                return ['status' => 'error', 'message' => 'Método de pagamento não suportado.'];
        }
    }

    /**
     * Executa a transação de Cartão de Crédito
     */
    private function executeCreditCard(array $payload): array {
        $config = new McClientConfig();
        $paymentLog = new PaymentLogs();
        $paymentLog->saveCustomerPaymentLog([
            "category" => "GATEWAY_OR_BUSINESS_ERROR",
            "result" => $payload
                ], $_SESSION['client_gcid']);
        // Recupera dados do cliente para o Gateway
        $efi = new EfiPayment();
        $notificationUrl = $config->getDomain() . "/" . $config->getUrlPublic() . "/notificationpayment";
        $response = $efi->credit_card(
                $payload['type'],
                $payload['payment'], // token
                $payload['method'],
                $payload['product'],
                $payload['price'],
                $payload['customer']['gcid'],
                $notificationUrl,
                $payload['customer']['name'],
                $payload['customer']['cpf'],
                $payload['customer']['contact'],
                $payload['customer']['email'],
                $payload['customer']['birth'],
                $payload['discount'],
                $payload['installments']
        );
        return [
            "allowed" => $response['allowed'],
            "message" => $response['message'],
            "status" => $response['status'],
            "paymentmethod" => $response['paymentmethod'],
            "charge_id" => $response['charge_id']
        ];
    }
    /**
     * Atualiza o status de um pagamento e gera histórico de mudança.
     * * @param string $chargeId ID da transação no gateway
     * @param string $customerGcid GCID do cliente
     * @param string $newStatus Novo status vindo da notificação
     * @param string $changedBy Origem da mudança (ex: efi, system, admin)
     * @param string|null $oldStatus Status anterior (opcional)
     * @return array
     */
    public function updatePaymentStatus(
            string $chargeId,
            string $customerGcid,
            string $newStatus,
            string $changedBy,
            ?string $oldStatus = null
    ): array {
        // validação básica
        if (empty($chargeId) || empty($customerGcid) || empty($newStatus) || empty($changedBy)) {
            return ['allowed' => false, 'message' => 'Não é permitido campo em branco'];
        }

        // busca o cliente
        $customer = (new Client())->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $customerGcid]]);
        if (!$customer) {
            return ['allowed' => false, 'message' => 'O cliente informado está incorreto.'];
        }

        // busca status de pagamento
        $paymentStatus = new PaymentStatus;
        $paymentStatus = (new PaymentStatus())->getQuery(
                single: true,
                customWhere: [
                    ['column' => 'description', 'value' => $newStatus],
                    ['column' => 'payment_config_id', 'value' => env('EFI_DB_CODE')]
                ]
        );

        if (!$paymentStatus) {
            return ['allowed' => false, 'message' => 'Status de pagamento inválido.'];
        }

        // busca o pagamento pelo charge_id
        $payment = (new SignaturePayment())->getQuery(
                single: true,
                customWhere: [['column' => 'payment_charge_id', 'value' => $chargeId]]
        );

        if (!$payment) {
            return ['allowed' => false, 'message' => 'Não foi encontrado uma ordem de pagamento para o charge_id.'];
        }

        // busca a assinatura associada
        $signature = new Signature;
        $signature = (new Signature())->getQuery(
                single: true,
                customWhere: [['column' => 'id', 'value' => $payment->getSignature_id()]]
        );

        if ($signature->getCustomer_id() !== $customer->getId()) {
            return ['allowed' => false, 'message' => 'O cliente informado está incorreto.'];
        }

        // atualiza pagamento com o novo status
        $signaturePayment = new SignaturePayment;
        $signaturePayment->setId($payment->getId());
        $signaturePayment->setPayment_status_id($paymentStatus->getId());
        $signaturePayment->setPayment_status($newStatus);
        $retorno = $signaturePayment->setSaveQuery();

        if (!$retorno) {
            return ['allowed' => false, 'message' => 'Erro ao atualizar status.'];
        }

        // cria registro no histórico de pagamentos
        $history = new SignaturePaymentHistory();
        $history->setSignature_payment_id($payment->getId());
        $history->setOld_status($oldStatus);
        $history->setNew_status($newStatus);
        $history->setChanged_by($changedBy);
        $history->setReason(EfiPaymentMessageHelper::translateStatus($newStatus, "pt_br"));
        $history->setSaveQuery();

        //Atualizar data da renovaçao
        if ($paymentStatus->getStatus() === 1) {
            $date_renovation = date('Y-m-d H:i:s', strtotime('+31 days'));
            if ($signature->getRenewal_cycle() === "anual") {
                $date_renovation = date('Y-m-d H:i:s', strtotime('+1 year'));
            } else {
                $date_renovation = date('Y-m-d H:i:s', strtotime('+31 days'));
            }
            $signatureUpdate = new Signature();
            $signatureUpdate->setId($signature->getId());
            $signatureUpdate->setDate_renovation($date_renovation);
            $signatureUpdate->setSaveQuery();
        }


        return ['allowed' => true, 'message' => EfiPaymentMessageHelper::translateStatus($newStatus, "pt_br")];
    }

    /**
     * Processa o estorno baseado na regra legal de 7 dias (Direito de Arrependimento).
     * * @param string $signatureGcid GCID da assinatura que será cancelada e estornada.
     * @return array Resposta contendo o status da operação e mensagem traduzida.
     */
    public function refundByRepentanceRule(string $signatureGcid): array {
        $translate = new \Microfw\Src\Main\Common\Helpers\Public\Translate\Translate();
        $paymentLog = new \Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs();

        // 1. Busca os dados da assinatura
        $signatureEntity = new \Microfw\Src\Main\Common\Entity\Public\Signature();
        $signature = $signatureEntity->getQuery(single: true, customWhere: [
            ['column' => 'gcid', 'value' => $signatureGcid]
        ]);

        if (!$signature) {
            return ['allowed' => false, 'message' => 'Assinatura não encontrada.'];
        }

        // 2. Cálculo do período de elegibilidade (7 dias)
        $startDate = new \DateTime($signature->getDate_start());
        $currentDate = new \DateTime();
        $interval = $startDate->diff($currentDate);
        $daysElapsed = (int) $interval->format("%r%a");

        // Se passou de 7 dias, não permite o estorno automático pela regra de arrependimento
        if ($daysElapsed > 7) {
            return [
                'allowed' => false,
                'message' => 'O prazo legal de 7 dias para estorno expirou. A assinatura será apenas cancelada.'
            ];
        }

        // 3. Localiza o pagamento associado que foi concluído (status 'paid')
        $paymentEntity = new \Microfw\Src\Main\Common\Entity\Public\SignaturePayment();
        $payment = $paymentEntity->getQuery(single: true, customWhere: [
            ['column' => 'signature_id', 'value' => $signature->getId()],
            ['column' => 'payment_status', 'value' => 'paid']
                ], order: "date_payment DESC");

        if (!$payment || empty($payment->getPayment_charge_id())) {
            return ['allowed' => false, 'message' => 'Nenhum pagamento aprovado foi encontrado para estorno.'];
        }

        // 4. Executa a chamada técnica ao Gateway
        $chargeId = $payment->getPayment_charge_id();
        $efi = new EfiPayment();
        $refundResult = $efi->executeGatewayRefund($chargeId);

        if ($refundResult['allowed']) {
            // Registro de log de sucesso
            $paymentLog->saveCustomerPaymentLog([
                "category" => "REFUND_SUCCESS",
                "message" => "Estorno de 7 dias processado com sucesso",
                "signature_gcid" => $signatureGcid,
                "charge_id" => $chargeId,
                "days_elapsed" => $daysElapsed
                    ], $_SESSION['client_gcid']);
        } else {
            // Registro de log de erro técnico
            $paymentLog->saveCustomerPaymentLog([
                "category" => "REFUND_FAILED",
                "error" => $refundResult['message'],
                "charge_id" => $chargeId
                    ], $_SESSION['client_gcid']);
        }

        return $refundResult;
    }
}
