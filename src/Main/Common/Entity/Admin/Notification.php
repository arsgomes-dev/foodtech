<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of Notification
 *
 * @author Ricardo Gomes
 */
class Notification extends ModelClass {

    protected $table_db = "notification";
    protected $table_columns_like_db = ['titles'];
    private $table_db_primaryKey = "id";
    private $id;
    private string $title_type;
    private string $title;
    private string $description;
    private $user_id;
    private $type;
    private string $description_type;
    private $status;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle_type() {
        if (isset($this->title_type)) {
            return $this->title_type;
        } else {
            return null;
        }
    }

    public function setTitle_type(string $title_type) {
        $this->title_type = $title_type;
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

    public function getType() {
        if (isset($this->type)) {
            return $this->type;
        } else {
            return null;
        }
    }

    public function setType(int $type) {
        $this->type = $type;
    }

    public function getDescription_type() {
        if (isset($this->description_type)) {
            return $this->description_type;
        } else {
            return null;
        }
    }

    public function setDescription_type(string $description_type) {
        $this->description_type = $description_type;
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
