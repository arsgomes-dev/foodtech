<?php

use Microfw\Src\Main\Common\Helpers\Admin\PhpMailer\SendEmail;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;

// Busca emails ativos
$cronFilter = new CronEmail();
$cronFilter->setStatus(1);
$crons = $cronFilter->getQuery();

if (empty($crons)) {
    exit('Nenhum email para enviar');
}

foreach ($crons as $cron) {

    // Validação básica
    if (empty($cron->getEmail())) {
        continue;
    }

    $email = new SendEmail();
    $email->email = [$cron->getEmail()];
    $email->nameMailer = [$cron->getNamemailer()];
    $email->subject = $cron->getSubject();
    $email->body = $cron->getMessagesend();

    $result = $email->send();

    if ($result === true || $result === 1) {

        // Remove ou marca como enviado
        $cron->setDeleteQuery();

        echo "Email enviado: {$cron->getEmail()}\n";
    } else {

        // Ideal registrar log de erro
        echo "Erro ao enviar email: {$cron->getEmail()}\n";
    }
}
