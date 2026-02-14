<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class Ticket extends ModelClass {

    protected $table_db = "ticket";
    protected $table_columns_like_db = ["title"];
    private $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    protected $table_columns_between_db = ['created_at'];
    private $id;
    private $customer_id;
    private bool $gcid_generation = false;
    private string $gcid;
    private $ticket_department_subdepartment_id;
    private string $title;
    private string $description;
    private $level;
    private $priority_id;
    private $date_send;
    private $date_closing;
    private $date_reading;
    private $response;
    private $message_reading_status;
    private int $user_id_updated;
    private int $user_id_reading;
    private $status;
    private string $closure_description;

    public function getTable_db_join() {
        if (isset($this->table_db_join)) {
            return $this->table_db_join;
        } else {
            return null;
        }
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

    public function getGcid_generation() {
        return $this->gcid_generation;
    }

    public function setGcid_generation($gcid_generation) {
        $this->gcid_generation = $gcid_generation;

        return $this;
    }

    public function getGcid() {
        if (isset($this->gcid)) {
            return $this->gcid;
        } else {
            return null;
        }
    }

    public function setGcid($gcid = null) {
        ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();
        return $this;
    }

    public function getTicket_department_subdepartment_id() {
        if (isset($this->ticket_department_subdepartment_id)) {
            return $this->ticket_department_subdepartment_id;
        } else {
            return null;
        }
    }

    public function setTicket_department_subdepartment_id(int $ticket_department_subdepartment_id) {
        $this->ticket_department_subdepartment_id = $ticket_department_subdepartment_id;
    }

    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getDescription() {
        if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }

    public function getLevel() {
        if (isset($this->level)) {
            return $this->level;
        } else {
            return null;
        }
    }

    public function setLevel(int $level) {
        $this->level = $level;
    }

    public function getPriority_id() {
        if (isset($this->priority_id)) {
            return $this->priority_id;
        } else {
            return null;
        }
    }

    public function setPriority_id(int $priority_id) {
        $this->priority_id = $priority_id;
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

    public function getDate_closing() {
        if (isset($this->date_closing)) {
            return $this->date_closing;
        } else {
            return null;
        }
    }

    public function setDate_closing($date_closing) {
        $this->date_closing = $date_closing;
    }

    public function getDate_reading() {
        if (isset($this->date_reading)) {
            return $this->date_reading;
        } else {
            return null;
        }
    }

    public function setDate_reading($date_reading) {
        $this->date_reading = $date_reading;
    }

    public function getResponse() {
        if (isset($this->response)) {
            return $this->response;
        } else {
            return null;
        }
    }

    public function setResponse(int $response) {
        $this->response = $response;
    }

    public function getMessage_reading_status() {
        if (isset($this->message_reading_status)) {
            return $this->message_reading_status;
        } else {
            return null;
        }
    }

    public function setMessage_reading_status(int $message_reading_status) {
        $this->message_reading_status = $message_reading_status;
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function setUser_id_updated(int $user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function getUser_id_reading() {
        if (isset($this->user_id_reading)) {
            return $this->user_id_reading;
        } else {
            return null;
        }
    }

    public function setUser_id_reading(int $user_id_reading) {
        $this->user_id_reading = $user_id_reading;
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

    public function getClosure_description() {
        if (isset($this->closure_description)) {
            return $this->closure_description;
        } else {
            return null;
        }
    }

    public function setClosure_description($closure_description) {
        $this->closure_description = $closure_description;
    }
}

?>