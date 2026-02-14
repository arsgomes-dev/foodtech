<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class TicketSendFile extends ModelClass {

    protected $table_db = "ticket_send_file";
    private $table_db_primaryKey = "id";
    private int $id;
    private int $ticket_send_id;
    private string $file;

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

    public function getTicket_send_id() {
        if (isset($this->ticket_send_id)) {
            return $this->ticket_send_id;
        } else {
            return null;
        }
    }

    public function setTicket_send_id(int $ticket_send_id) {
        $this->ticket_send_id = $ticket_send_id;
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
 }
?>