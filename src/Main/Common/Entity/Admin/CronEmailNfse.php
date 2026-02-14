<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class CronEmailNfse extends ModelClass {

    protected $table_db = "cron_emails_nfse";
    private $table_db_primaryKey = "id";
    private int $id;
    private string $signature_payment_gcid;
    private int $status;
    private string $email;
    private string $namemailer;
    private string $subject;
    private string $messagesend;
    private $files;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getSignature_payment_gcid() {
        if (isset($this->signature_payment_gcid)) {
            return $this->signature_payment_gcid;
        } else {
            return null;
        }
    }

    public function setSignature_payment_gcid(string $signature_payment_gcid) {
        $this->signature_payment_gcid = $signature_payment_gcid;
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setStatus(int $status) {
        $this->status = $status;
    }

    public function getEmail() {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return null;
        }
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getNamemailer() {
        if (isset($this->namemailer)) {
            return $this->namemailer;
        } else {
            return null;
        }
    }

    public function setNamemailer(string $namemailer) {
        $this->namemailer = $namemailer;
    }

    public function getSubject() {
        if (isset($this->subject)) {
            return $this->subject;
        } else {
            return null;
        }
    }

    public function setSubject(string $subject) {
        $this->subject = $subject;
    }

    public function getMessagesend() {
        if (isset($this->messagesend)) {
            return $this->messagesend;
        } else {
            return null;
        }
    }

    public function setMessagesend(string $messagesend) {
        $this->messagesend = $messagesend;
    }

    public function getFiles() {
        if (isset($this->files)) {
            return $this->files;
        } else {
            return null;
        }
    }

    public function setFiles($files) {
        $this->files = $files;
    }
}

?>