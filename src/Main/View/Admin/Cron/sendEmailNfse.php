<?php

use Microfw\Src\Main\Common\Helpers\Admin\PhpMailer\SendEmail;
use Microfw\Src\Main\Common\Entity\Admin\CronEmailNfse;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;

// Busca notas pendentes
$cronFilter = new CronEmailNfse();
$cronFilter->setStatus(1);
$crons = $cronFilter->getQuery();

if (empty($crons)) {
    exit("Nenhuma NFS-e para enviar\n");
}

foreach ($crons as $cron) {

    // Validação básica
    if (empty($cron->getEmail()) || empty($cron->getSignature_payment_gcid())) {
        continue;
    }

    // Busca pagamento pelo GCID
    $signaturePaymentRepo = new SignaturePayment();
    $signaturePaymentRepo->setTable_db_primaryKey('gcid');

    $signaturePayment = $signaturePaymentRepo->getQuery(
            single: true,
            customWhere: [
                ['column' => 'gcid', 'value' => $cron->getSignature_payment_gcid()]
            ]
    );

    if (!$signaturePayment || empty($signaturePayment->getId())) {
        continue; // pagamento não encontrado
    }

    // Monta email
    $email = new SendEmail();
    $email->email = [$cron->getEmail()];
    $email->nameMailer = [$cron->getNamemailer()];
    $email->subject = $cron->getSubject();
    $email->body = $cron->getMessagesend();

    // Anexos (NFS-e)
    $files = json_decode($cron->getFiles(), true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($files)) {
        $validFiles = [];

        foreach ($files as $file) {
            if (is_string($file) && file_exists($file)) {
                $validFiles[] = $file;
            }
        }

        if (!empty($validFiles)) {
            $email->files = $validFiles;
        }
    }

    // Envio
    $result = $email->send();

    if ($result === true || $result === 1) {

        // Marca pagamento como NFS-e enviada
        $signaturePaymentUpdate = new SignaturePayment();
        $signaturePaymentUpdate->setId($signaturePayment->getId());
        $signaturePaymentUpdate->setNfse_sent(1);
        $signaturePaymentUpdate->setSaveQuery();

        // Remove da fila de cron
        $cron->setDeleteQuery();

        echo "NFS-e enviada para {$cron->getEmail()}\n";
    } else {
        echo "Erro ao enviar NFS-e para {$cron->getEmail()}\n";
    }
}
