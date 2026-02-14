<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of DepartmentOccupation
 *
 * @author Ricardo Gomes
 */
class DepartmentOccupation extends ModelClass {

    protected $table_db = "department_occupations";
    protected $table_columns_like_db = ['title'];
    private $table_db_primaryKey = "id";
    private int $id;
    private int $department_id;
    private int $user_id_created;
    private int $user_id_updated;
    private string $title;

    public function getId(){
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getDepartment_id(){
        if (isset($this->department_id)) {
            return $this->department_id;
        } else {
            return null;
        }
    }

    public function getUser_id_created(){
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function getUser_id_updated(){
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function getTitle(){
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function setDepartment_id(int $department_id){
        $this->department_id = $department_id;
    }

    public function setUser_id_created(int $user_id_created){
        $this->user_id_created = $user_id_created;
    }

    public function setUser_id_updated(int $user_id_updated){
        $this->user_id_updated = $user_id_updated;
    }

    public function setTitle(string $title){
        $this->title = $title;
    }
}
