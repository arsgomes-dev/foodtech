<?php

namespace Microfw\Src\Main\Common\Service\Public\Signatures;

use Microfw\Src\Main\Common\Entity\Public\SignatureTerms;
use Microfw\Src\Main\Common\Helpers\General\IpClient\IpClient;
use Microfw\Src\Main\Common\Entity\Public\SignatureAutoRenewHistory;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\PaymentStatus;
use Microfw\Src\Main\Common\Entity\Public\PaymentMethod;

class ManageSignatures {

    function setAutoRenewHistory($terms_id, $signature_id) {
        $terms = new SignatureTerms();
        $terms = $terms->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $terms_id]]);
        $ipAddress = (new IpClient())->getClientIp();
        $history = new SignatureAutoRenewHistory();
        $history->setSignature_id($signature_id);
        $history->setTerm_version($terms->getVersion());
        $history->setTerm_title($terms->getTitle());
        $history->setTerm_text($terms->getTerm());
        $history->setTerm_hash(hash('sha256', $terms->getTerm()));
        $history->setAccepted_at(date('Y-m-d H:i:s'));
        $history->setIp_address($ipAddress);
        $history->setUser_agent($_SERVER['HTTP_USER_AGENT'] ?? null);
        $history->setAccepted_by('customer');
        $history->setSource('web');
        $history->setSaveQuery();
    }

    function setSignaturePayment($paymentload) {
        /** STATUS E MÉTODO DE PAGAMENTO */
        $paymentStatus = new PaymentStatus();
        $paymentStatus = $paymentStatus->getQuery(single: true, customWhere: [
            ['column' => 'description', 'value' => $paymentload['gateway_status']],
            ['column' => 'payment_config_id', 'value' => $paymentload['gateway_config_id']]
        ]);
        $paymentMethod = new PaymentMethod();
        $paymentMethod = $paymentMethod->getQuery(single: true, customWhere: [
            ['column' => 'payment_method', 'value' => $paymentload['gateway_payment_method']],
            ['column' => 'payment_config_id', 'value' => $paymentload['gateway_config_id']]
        ]);
        /** UPDATE PAGAMENTO * */
        $paymentUpdate = new SignaturePayment();
        $paymentUpdate->setId($paymentload['id']);
        $paymentUpdate->setPayment_status_id($paymentStatus->getId());
        $paymentUpdate->setPayment_status($paymentload['gateway_status']);
        $paymentUpdate->setPayment_config_id($paymentload['gateway_config_id']);
        $paymentUpdate->setPayment_charge_id($paymentload['gateway_charge_id']);
        $paymentUpdate->setPayment_method($paymentload['gateway_payment_method']);
        $paymentUpdate->setDate_payment(date('Y-m-d H:i:s'));
        $paymentUpdate->setPayment_token($paymentload['token']);
        $paymentUpdate->setCard_mask($paymentload['card_mask']);
        $paymentUpdate->setInstallment($paymentload['installments']);
        $paymentUpdate->setPayment_method_id($paymentMethod->getId());
        $paymentUpdate->setSaveQuery();
    }
}
