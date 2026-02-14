<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class TicketDepartmentSubdepartmentPriority extends ModelClass {

    protected $table_db = "ticket_department_subdepartment_priority";
    private $table_db_primaryKey = "id";
    private int $id;
    private int $level;
    private string $title;
    private int $deadline;

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

    public function getDeadline() {
        if (isset($this->deadline)) {
            return $this->deadline;
        } else {
            return null;
        }
    }

    public function setDeadline(int $deadline) {
        $this->deadline = $deadline;
    }
 }
?>