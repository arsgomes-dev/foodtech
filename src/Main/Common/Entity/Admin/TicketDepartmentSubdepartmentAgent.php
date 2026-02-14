<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class TicketDepartmentSubdepartmentAgent extends ModelClass {

    protected $table_db = "ticket_department_subdepartment_agent";
    private $table_db_primaryKey = "id";
    private int $id;
    private int $ticket_department_subdepartment_id;
    private int $ticket_agent_id;

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

    public function getTicket_agent_id() {
        if (isset($this->ticket_agent_id)) {
            return $this->ticket_agent_id;
        } else {
            return null;
        }
    }

    public function setTicket_agent_id(int $ticket_agent_id) {
        $this->ticket_agent_id = $ticket_agent_id;
    }
 }
?>