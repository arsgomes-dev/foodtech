<?php

namespace Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller;

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\SignaturePaymentHistory;
use Microfw\Src\Main\Common\Entity\Public\PaymentStatus;
use Microfw\Src\Main\Common\Entity\Public\Client;

class UpdatePayment {

    function status($charge_id, $custom_id, $status, $changed_by, $status_previous) {
        $translate = new Translate();
        if (!empty($charge_id) && isset($charge_id) &&
                !empty($custom_id) && isset($custom_id) &&
                !empty($status) && isset($status) &&
                !empty($changed_by) && isset($changed_by)) {

            $customer = new Client;
            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $custom_id]]);

            if ($customer) {
                $paymentStatus = new PaymentStatus;
                $paymentStatus = $paymentStatus->getQuery(single: true, customWhere: [['column' => 'description', 'value' => $status], ['column' => 'payment_config_id', 'value' => env('EFI_DB_CODE')]]);

                $signaturePaymentSearch = new SignaturePayment;
                $signaturePaymentSearch = $signaturePaymentSearch->getQuery(single: true, customWhere: [['column' => 'payment_charge_id', 'value' => $charge_id]]);
                if ($signaturePaymentSearch) {
                    $signature = new Signature;
                    $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePaymentSearch->getSignature_id()]]);
                    if ($signature->getCustomer_id() === $customer->getId()) {
                        $signaturePayment = new SignaturePayment;
                        $signaturePayment->setId($signaturePaymentSearch->getId());
                        $signaturePayment->setPayment_status_id($paymentStatus->getId());
                        $signaturePayment->setPayment_status($status);
                        $retorno = $signaturePayment->setSaveQuery();
                        if ($retorno === 1) {
                            $reason = "";
                            if ($status === "new") {
                                $reason = 'Tudo pronto! Escolha como deseja pagar para finalizar.';
                            } elseif ($status === "waiting") {
                                $reason = 'Recebemos seu pedido e estamos aguardando a confirmação do pagamento. Acompanhe o andamento no menu faturas.';
                            } elseif ($status === "identified") {
                                $reason = 'Opa! Já identificamos seu pagamento e estamos processando. Acompanhe o andamento no menu faturas.';
                            } elseif ($status === "approved") {
                                $reason = 'Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.';
                            } elseif ($status === "paid") {
                                $reason = 'Sucesso! Seu pagamento foi confirmado.';
                            } elseif ($status === "unpaid") {
                                $reason = 'Ops, não conseguimos confirmar o pagamento. Verifique os dados.';
                            } elseif ($status === "refunded") {
                                $reason = 'O valor foi estornado e devolvido para você.';
                            } elseif ($status === "contested") {
                                $reason = 'O pagamento está em análise pela operadora do cartão.';
                            } elseif ($status === "canceled") {
                                $reason = 'Esta cobrança foi cancelada.';
                            } elseif ($status === "settled") {
                                $reason = 'Pagamento confirmado manualmente pela nossa equipe.';
                            } elseif ($status === "expired") {
                                $reason = 'O prazo expirou. Por favor, tente novamente.';
                            } else {
                                $reason = 'Verificando status do pagamento...';
                            }
                            $signatureHistory = new SignaturePaymentHistory();
                            $signatureHistory->setSignature_payment_id($signaturePaymentSearch->getId());
                            $signatureHistory->setOld_status($status_previous);
                            $signatureHistory->setNew_status($status);
                            $signatureHistory->setChanged_by($changed_by);
                            $signatureHistory->setReason($reason);
                            $signatureHistory->setSaveQuery();
                            return ['allowed' => true, 'message' => 'Status atualizado com sucesso.'];
                        } else {
                            return ['allowed' => false, 'message' => 'Erro ao atualizar status.'];
                        }
                    } else {
                        return ['allowed' => false, 'message' => 'O cliente informado está incorreto.'];
                    }
                } else {
                    return ['allowed' => false, 'message' => 'Não foi encontrado uma ordem de pagamento para o charge_id.'];
                }
            } else {
                return ['allowed' => false, 'message' => 'O cliente informado está incorreto.'];
            }
        } else {
            return ['allowed' => false, 'message' => 'Não é permitido campo em branco'];
        }
    }
}
