<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class TicketSend extends ModelClass {

    protected $table_db = "ticket_send";
    private $table_db_primaryKey = "id";
    private int $id;
    private int $ticket_id;
    private int $user_id;
    private int $customer_id;
    private string $message;
    private string $file;
    private $date_send;
    private $date_read;
    private int $status;

    public function setTable_db_primaryKey($table) {
        $this->table_db_primaryKey = $table;
    }

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

    public function getTicket_id() {
        if (isset($this->ticket_id)) {
            return $this->ticket_id;
        } else {
            return null;
        }
    }

    public function setTicket_id(int $ticket_id) {
        $this->ticket_id = $ticket_id;
    }

    public function getUser_id() {
        if (isset($this->user_id)) {
            return $this->user_id;
        } else {
            return null;
        }
    }

    public function setUser_id(int $user_id) {
        $this->user_id = $user_id;
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function setCustomer_id(int $customer_id) {
        $this->customer_id = $customer_id;
    }

    public function getMessage() {
        if (isset($this->message)) {
            return $this->message;
        } else {
            return null;
        }
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getFile() {
        if (isset($this->file)) {
            return $this->file;
        } else {
            return null;
        }
    }

    public function setFile(string $file) {
        $this->file = $file;
    }

    public function getDate_send() {
        if (isset($this->date_send)) {
            return $this->date_send;
        } else {
            return null;
        }
    }

    public function setDate_send($date_send) {
        $this->date_send = $date_send;
    }

    public function getDate_read() {
        if (isset($this->date_read)) {
            return $this->date_read;
        } else {
            return null;
        }
    }

    public function setDate_read($date_read) {
        $this->date_read = $date_read;
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
}

?>