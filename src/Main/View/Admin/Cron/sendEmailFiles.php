<?php

use Microfw\Src\Main\Common\Helpers\Admin\PhpMailer\SendEmail;
use Microfw\Src\Main\Common\Entity\Admin\CronEmailFiles;

// Busca emails ativos
$cronFilter = new CronEmailFiles();
$cronFilter->setStatus(1);
$crons = $cronFilter->getQuery();

if (empty($crons)) {
    exit('Nenhum email com anexo para enviar');
}

foreach ($crons as $cron) {

    // Valida email
    if (empty($cron->getEmail())) {
        continue;
    }

    $email = new SendEmail();
    $email->email = [$cron->getEmail()];
    $email->nameMailer = [$cron->getNamemailer()];
    $email->subject = $cron->getSubject();
    $email->body = $cron->getMessagesend();

    // Decodifica arquivos
    $files = json_decode($cron->getFiles(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        continue; // JSON inválido
    }

    // Garante que seja array
    if (!empty($files) && is_array($files)) {
        $email->files = $files;
    }

    $result = $email->send();

    if ($result === true || $result === 1) {
        $cron->setDeleteQuery();
        echo "Email com anexo enviado: {$cron->getEmail()}\n";
    } else {
        echo "Erro ao enviar email: {$cron->getEmail()}\n";
    }
}
