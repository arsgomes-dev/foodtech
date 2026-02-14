<?php

namespace Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller;

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;

class EfiPaymentMessageHelper {

    public static function translateStatus(string $status, string $lang): string {
        $translate = new Translate();
        switch ($status) {
            case 'new':
                $msg = 'Tudo pronto! Escolha como deseja pagar para finalizar.';
                return "3->" . $translate->translate($msg, $lang);
            case 'waiting':
                $msg = 'Recebemos seu pedido e estamos aguardando a confirmação do pagamento. Acompanhe o andamento no menu faturas.';
                return "1->" . $translate->translate($msg, $lang);
            case 'identified':
                $msg = 'Opa! Já identificamos seu pagamento e estamos processando. Acompanhe o andamento no menu faturas.';
                return "1->" . $translate->translate($msg, $lang);
            case 'approved':
                $msg = 'Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.';
                return "1->" . $translate->translate($msg, $lang);
            case 'paid':
                $msg = 'Sucesso! Seu pagamento foi confirmado.';
                return "1->" . $translate->translate($msg, $lang);
            case 'unpaid':
                $msg = 'Ops, não conseguimos confirmar o pagamento. Verifique os dados.';
                return "2->" . $translate->translate($msg, $lang);
            case 'refunded':
                $msg = 'O valor foi estornado e devolvido para você.';
                return "2->" . $translate->translate($msg, $lang);
            case 'contested':
                $msg = 'O pagamento está em análise pela operadora do cartão.';
                return "2->" . $translate->translate($msg, $lang);
            case 'canceled':
                $msg = 'Esta cobrança foi cancelada.';
                return "2->" . $translate->translate($msg, $lang);
            case 'settled':
                $msg = 'Pagamento confirmado manualmente pela nossa equipe.';
                return "1->" . $translate->translate($msg, $lang);
            case 'expired':
                $msg = 'O prazo expirou. Por favor, tente novamente.';
                return "2->" . $translate->translate($msg, $lang);
            default:
                $msg = 'Verificando status do pagamento...';
                return "2->" . $translate->translate($msg, $lang);
        }
    }
}
