<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of PrivilegeType
 *
 * @author Ricardo Gomes
 */
class PrivilegeType extends ModelClass {
    protected $logTimestamp = false;
    protected $table_db = "privilege_type";
    protected $table_columns_like_db = ['description'];
    private $table_db_primaryKey = "id";
    private int $id;
    private string $description;
    private string $description_type;

    public function getId(){
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getDescription(){
         if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function getDescription_type(){ 
        if (isset($this->description_type)) {
            return $this->description_type;
        } else {
            return null;
        }
    }
    public function setId(int $id){
        $this->id = $id;
    }

    public function setDescription(string $description){
        $this->description = $description;
    }

    public function setDescription_type(string $description_type){
        $this->description_type = $description_type;
    }
}
