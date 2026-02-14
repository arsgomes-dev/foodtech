<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class TicketDepartmentSubdepartment extends ModelClass {

    protected $table_db = "ticket_department_subdepartment";
    protected $table_columns_like_db = ["title"];
    private $table_db_primaryKey = "id";
    private int $id;
    private int $ticket_department_id;
    private string $title;
    private int $status;
    private int $ticket_department_subdepartment_priority_id;
    private int $user_id_created;
    private int $user_id_updated;

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

    public function getTicket_department_id() {
        if (isset($this->ticket_department_id)) {
            return $this->ticket_department_id;
        } else {
            return null;
        }
    }

    public function setTicket_department_id(int $ticket_department_id) {
        $this->ticket_department_id = $ticket_department_id;
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

    public function getTicket_department_subdepartment_priority_id() {
        if (isset($this->ticket_department_subdepartment_priority_id)) {
            return $this->ticket_department_subdepartment_priority_id;
        } else {
            return null;
        }
    }

    public function setTicket_department_subdepartment_priority_id(int $ticket_department_subdepartment_priority_id) {
        $this->ticket_department_subdepartment_priority_id = $ticket_department_subdepartment_priority_id;
    }

    public function getUser_id_created() {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function setUser_id_created(int $user_id_created) {
        $this->user_id_created = $user_id_created;
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
}

?>