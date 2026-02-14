<?php

namespace Microfw\Src\Main\Common\Helpers\Admin\PhpMailer;

use Microfw\Src\Main\Common\Entity\Admin\Mailer;
use Microfw\Src\Main\Common\Helpers\Admin\PhpMailer\PHPMailer;

class SendEmail {

    public array $email;
    public array $nameMailer;
    public string $subject;
    public string $body;
    public array $files = [];

    public function send() {
        $mail = new PHPMailer(true);
        $mailer = new Mailer();
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8'; // Codificação correta
            $mail->Encoding = 'base64'; // Opcional, mas ajuda a garantir a leitura correta                         // Send using SMTP
            $mail->Host = $mailer->getHost();                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = $mailer->getUsername();        // SMTP username
            $mail->Password = $mailer->getPasswd();                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = $mailer->getPort();
            $mail->setFrom($mailer->getUsername(), $mailer->getName());
            $mail->addAddress($this->email[0]);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body = $this->body;
            if ($this->files !== null && $this->files !== "") {
                $file = $this->files;
                foreach ($file as $chave => $valor) {
                    $mail->addAttachment($valor); // caminho absoluto ou relativo
                }
            }

            $mail->SMTPDebug = 2;
            $mail->send();
            return 1;
        } catch (Exception $e) {
            return 2;
        }
    }
}
