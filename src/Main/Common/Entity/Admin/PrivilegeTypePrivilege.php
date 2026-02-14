<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of PrivilegeTypePrivilege
 *
 * @author Ricardo Gomes
 */
class PrivilegeTypePrivilege extends ModelClass {
    
    protected $logTimestamp = false;
    protected $table_db = "privilege_type_privilege";
    private int $id;
    private int $privilege_id;
    private int $privilege_type_id;
    
    public function getId(){
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getPrivilege_id(){
        if (isset($this->privilege_id)) {
            return $this->privilege_id;
        } else {
            return null;
        }
    }

    public function getPrivilege_type_id(){
        if (isset($this->privilege_type_id)) {
            return $this->privilege_type_id;
        } else {
            return null;
        }
    }
    public function setId(int $id){
        $this->id = $id;
    }

    public function setPrivilege_id(int $privilege_id){
        $this->privilege_id = $privilege_id;
    }

    public function setPrivilege_type_id(int $privilege_type_id){
        $this->privilege_type_id = $privilege_type_id;
    }



}
