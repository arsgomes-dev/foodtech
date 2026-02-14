<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Landing\Controller;

/**
 * Description of PaymentLogs
 *
 * @author Ricardo Gomes
 */
class PaymentLogs {

    /**
     * Salva logs de erro detalhados (H:i:s) em diretório configurado via env.
     * * @param mixed $message O conteúdo do erro (string ou array).
     * @param string $clientGcid O GCID do cliente para identificação do arquivo.
     * @return void
     */
    function saveCustomerPaymentLog($message, $clientGcid) {
        // Montagem do diretório utilizando as variáveis de ambiente fornecidas
        $diretorioLogs = $_SERVER['DOCUMENT_ROOT'] .
                env('FOLDER_LOGS') .
                env('FOLDER_LOGS_CUSTOMERS') .
                env('FOLDER_LOGS_PAYMENTS');

        // Criação do diretório com permissão caso não exista
        if (!file_exists($diretorioLogs)) {
            mkdir($diretorioLogs, 0777, true);
        }

        // Nome do arquivo com Data e GCID (Ex: 2026-01-25_GCID12345.log)
        $fileName = date('Y-m-d') . "_" . $clientGcid . ".log";
        $filePath = $diretorioLogs . DIRECTORY_SEPARATOR . $fileName;

        // Timestamp completo para a linha do log: [25-01-2026 14:30:05]
        $fullTimestamp = date('d-m-Y H:i:s');

        // Tratamento de mensagens (converte arrays para JSON legível)
        $content = is_array($message) || is_object($message) ? json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $message;

        $logLine = "---" . PHP_EOL;
        $logLine .= "[{$fullTimestamp}]" . PHP_EOL;
        $logLine .= $content . PHP_EOL;

        // Gravação incremental
        file_put_contents($filePath, $logLine, FILE_APPEND);
    }
}
