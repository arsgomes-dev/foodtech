<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class Agent extends ModelClass {

    protected $table_db = "agent";
    private $table_db_primaryKey = "id";
    private int $id;
    private int $user_id;
    private int $status;

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