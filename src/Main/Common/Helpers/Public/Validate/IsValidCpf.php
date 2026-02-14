<?php

namespace Microfw\Src\Main\Common\Helpers\Public\Validate;

/**
 * Description of isValidCpf
 *
 * @author Ricardo Gomes
 */
class IsValidCpf {

    function getIsValidCPF($cpf) {
        // Remove any non-digit characters
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Check if it has exactly 11 digits
        if (strlen($cpf) != 11) {
            return false;
        }

        // Reject CPFs with all digits the same (e.g. 11111111111)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Validate check digits
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }

            $digit = (10 * $sum) % 11;
            $digit = ($digit == 10) ? 0 : $digit;

            if ($cpf[$t] != $digit) {
                return false;
            }
        }

        return true;
    }
}
