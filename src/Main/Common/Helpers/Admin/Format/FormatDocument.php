<?php

namespace Microfw\Src\Main\Common\Helpers\Admin\Format;

class FormatDocument {

    function formatCnpj($documentNumber) {
        $documentNumber = preg_replace('/\D/', '', $documentNumber);

        if (strlen($documentNumber) === 11) {
            // CPF
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $documentNumber);
        } elseif (strlen($documentNumber) === 14) {
            // CNPJ
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $documentNumber);
        }

        return $documentNumber;
    }

    function formatPhoneNumber($phoneNumber) {
        // Remove tudo que não for número
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Celular com DDD (11 dígitos)
        if (strlen($phoneNumber) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phoneNumber);
        }

        // Telefone fixo com DDD (10 dígitos)
        if (strlen($phoneNumber) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phoneNumber);
        }

        // Número sem DDD (8 ou 9 dígitos)
        if (strlen($phoneNumber) === 9) {
            return preg_replace('/(\d{5})(\d{4})/', '$1-$2', $phoneNumber);
        }

        if (strlen($phoneNumber) === 8) {
            return preg_replace('/(\d{4})(\d{4})/', '$1-$2', $phoneNumber);
        }

        // Retorna sem formatação se não bater com nenhum padrão
        return $phoneNumber;
    }
}
