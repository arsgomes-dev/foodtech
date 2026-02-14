<?php

namespace Microfw\Src\Main\Controller\Public\AccessPlans;

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\PaymentStatus;
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;
use Microfw\Src\Main\Controller\Public\Signature\GeneratePaymentOrder;
use Microfw\Src\Main\Controller\Landing\Controller\PaymentLogs;

class CheckPlan {

    public static function checkPlan() {
        /**
         * CAPTURA DE ERROS CRÍTICOS (CÓDIGO)
         * Registra erros de sintaxe ou fatais no log customizado antes de encerrar.
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

        $translate = new Translate();
        $config = new McClientConfig;

        $now = date('Y-m-d H:i');

        // Cliente logado
        if (empty($_SESSION['client_id'])) {
            ProtectedPage::protectedPage();
        }

        $customer_id = (int) $_SESSION['client_id'];

        /* -----------------------------------------------
         * 1. Carregar lista de status de pagamento válidos
         * ----------------------------------------------- */
        $status_payments = [];

        $statusPayments = new PaymentStatus();
        $statusPayments->setStatus(1);
        $statusPayments = $statusPayments->getQuery();
        foreach ($statusPayments as $status) {
            $status_payments[] = $status->getId();
        }


        $status_payments_waiting = [];

        $statusPayments_waiting = new PaymentStatus();
        $statusPayments_waiting->setStatus(3);
        $statusPayments_waiting = $statusPayments_waiting->getQuery();
        foreach ($statusPayments_waiting as $status) {
            $status_payments_waiting[] = $status->getId();
        }
        /* -----------------------------------------------
         * 2. Buscar assinatura ativa do cliente
         * ----------------------------------------------- */
        $signature = new Signature();

        $signature = $signature->getQuery(single: true, customWhere: [['column' => 'customer_id', 'value' => $customer_id], ['column' => 'status', 'value' => 1]]);

        if (!$signature || !$signature->getId()) {
            return ['allowed' => false, 'plan_active' => false, 'message' => $translate->translate('Nenhum plano de assinatura ativo encontrado.', $_SESSION['client_lang'])];
        }
        /* ---------------------------
          /* -----------------------------------------------
         * 5. Buscar último pagamento da assinatura
         * ----------------------------------------------- */
        $payments = new SignaturePayment();
        $payments->setSignature_id($signature->getId());
        $payments = $payments->getQuery(
                single: true,
                whereNot: ["date_payment" => null],
                limit: 1,
                order: 'date_due DESC'
        );
        if ($payments === null) {
            return ['allowed' => false, 'plan_active' => true, 'plan_payment' => false, 'message' => $translate->translate('Nenhum pagamento encontrado para esta assinatura.', $_SESSION['client_lang'])];
        }
        $payment = new SignaturePayment();
        $payment = $payments;
        /* -----------------------------------------------
         * 4. Verificar validade da fatura
         * ----------------------------------------------- */

        if ($now > $signature->getDate_renovation()) {
            $payments = new SignaturePayment();
            $payments->setSignature_id($signature->getId());
            $payments = $payments->getQuery(limit: 1,
                    whereNull: ['date_payment'],
                    order: 'date_due DESC'
            );

            if (count($payments) <= 0) {
                $generatePayment = new GeneratePaymentOrder;
                $returnGenerate = $generatePayment->generateOrder($signature->getId());
                if (!$signature) {
                    $paymentLog->saveCustomerPaymentLog([
                        "category" => "CheckPlan_ERROR",
                        "details" => "Erro ao gerar pagamento no CheckPlan",
                        "gcid_signature" => $signature->getGcid()
                            ], $_SESSION['client_gcid']);
                }
            }

            return ['allowed' => false, 'plan_active' => true, 'plan_payment' => false, 'plan_expired' => true, 'message' => $translate->translate('Plano de assinatura vencido. Renove para continuar.', $_SESSION['client_lang'])];
        }


        /* -----------------------------------------------
         * 5. Verificar se status do pagamento é válido
         * ----------------------------------------------- */

        if (!in_array($payment->getPayment_status_id(), $status_payments)) {
            if (!in_array($payment->getPayment_status_id(), $status_payments_waiting)) {
                return ['allowed' => false, 'plan_active' => true, 'plan_payment' => false, 'plan_expired' => true, 'message' => $translate->translate('Nenhum pagamento encontrado para esta assinatura.', $_SESSION['client_lang'])];
            } else {
                return ['allowed' => false, 'plan_active' => true, 'plan_payment' => true, 'message' => $translate->translate('Recebemos seu pedido e estamos aguardando a confirmação do pagamento. Acompanhe o andamento no menu faturas.', $_SESSION['client_lang'])];
            }
        }


        /* -----------------------------------------------
         * 6. Tudo certo: plano ativo
         * ----------------------------------------------- */

        $accessPlan = new AccessPlan;
        $accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);
        if (!$accessPlan->getId()) {
            return ['allowed' => false, 'plan_active' => false, 'plan_payment' => false, 'message' => $translate->translate('Nenhum plano de assinatura ativo encontrado.', $_SESSION['client_lang'])];
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['client_plan'] = true;
        $_SESSION['client_plan_code'] = $accessPlan->getId();
        $_SESSION['client_plan_title'] = $accessPlan->getTitle();
        $_SESSION['client_plan_export_enabled'] = (int) $accessPlan->getExport_enabled();
        $_SESSION['client_plan_reports_enabled'] = (int) $accessPlan->getReports_enabled();
        $_SESSION['client_plan_max_foods'] = $accessPlan->getMax_foods();
        $_SESSION['client_plan_max_meals_daily'] = $accessPlan->getMax_meals_daily();
        $_SESSION['client_plan_message'] = $translate->translate('Plano de assinatura ativo.', $_SESSION['client_lang']);
        return ['allowed' => true, 'plan_active' => true, 'plan_payment' => true];
    }
}
