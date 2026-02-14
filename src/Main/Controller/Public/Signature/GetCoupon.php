<?php

namespace Microfw\Src\Main\Controller\Public\Signature;

use Microfw\Src\Main\Common\Entity\Public\AccessPlansCoupon;

/**
 * Description of GetCoupon
 *
 * @author Ricardo Gomes
 */
class GetCoupon {

    function searchCoupon($code) {
       if ($code !== null && $code !== "") {
            $coupon = new AccessPlansCoupon();
            // Busca o cupom pelo código
            $coupon = $coupon->getQuery(single: true, customWhere: [['column' => 'coupon', 'value' => mb_strtolower($code)]]);
            
            if ($coupon !== null) {
                // Verifica se tem ID válido
                if ($coupon->getGcid() !== null) {
                    // Verifica se ainda há quantidade disponível
                    if ($coupon->getQuantity_used() < $coupon->getAmount_use()) {
                        
                        // SUCESSO
                        return [
                            'status' => true, 
                            'discount_percent' => $coupon->getDiscount(), 
                            // Correção aqui: $coupon é objeto, usamos getDiscount() e não ['discount_percent']
                            'message' => 'Cupom de ' . floatval($coupon->getDiscount()) . '% aplicado!', 
                            'code_coupon' => $coupon->getId()
                        ];

                    } else {
                        return ['status' => false, 'discount_percent' => 0.00, 'message' => 'Este cupom já foi utilizado ou esgotado!'];
                    }
                } else {
                    return ['status' => false, 'discount_percent' => 0.00, 'message' => 'Cupom inválido!'];
                }
            } else {
                return ['status' => false, 'discount_percent' => 0.00, 'message' => 'Cupom não localizado!'];
            }
        }
        return ['status' => false, 'discount_percent' => 0.00, 'message' => 'Código inválido.'];
    }
}
