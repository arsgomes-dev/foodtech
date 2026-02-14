<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Public\Signature;

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

// Validação de Sessão Protegida
ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\SignaturePaymentInvoice;

/**
 * Description of GeneratePaymentOrder
 *
 * @author Ricardo Gomes
 */
class GeneratePaymentOrder {

    function generateOrder($signature) {
        $payment = new SignaturePayment;
        $gcid_payment = $payment->getGenerateUniqueGcid(new SignaturePayment);
        $payment->setGcid($gcid_payment);
        $payment->setSignature_id($signature);
        $payment->setDate_billing(date('Y-m-d H:i:s'));
        $payment->setDate_due(date('Y-m-d H:i:s', strtotime('+5 days')));
        $retorno_payment = $payment->setSaveQuery();

        if ($retorno_payment === 2) {
            /** Geração da Invoice Relacional vinculada ao pagamento */
            $paymentInvoice = new SignaturePaymentInvoice;
            $paymentInvoice->setGcid($paymentInvoice->getGenerateUniqueGcid(new SignaturePaymentInvoice));
            $paymentInvoice->setSignature_payment_gcid($gcid_payment);
            $paymentInvoice->setSaveQuery();
            return true;
        } else {
            return false;
        }
    }
}
