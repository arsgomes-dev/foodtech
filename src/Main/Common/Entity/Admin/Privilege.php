<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of Privilege
 *
 * @author Ricardo Gomes
 */
class Privilege extends ModelClass {

    //protected $logTimestamp = false;
    protected $table_db = "privilege";
    protected $table_columns_like_db = ['description'];
    private $table_db_primaryKey = "id";
    private int $id;
    private string $description;
    private int $user_id_created;
    private int $user_id_updated;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getDescription() {
        if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function getUser_id_created() {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setUser_id_created(int $user_id_created): void {
        $this->user_id_created = $user_id_created;
    }

    public function setUser_id_updated(int $user_id_updated): void {
        $this->user_id_updated = $user_id_updated;
    }
}
