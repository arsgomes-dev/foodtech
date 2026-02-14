<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of LoginAttempts
 *
 * @author Ricardo Gomes
 */
class LoginAttempts extends ModelClass {

    protected $table_db = "loginattempts";
    protected $logTimestamp = false;
    private $table_db_primaryKey = "user_id";
    private int $user_id;
    private int $time;

    /**
     * Get the value of id
     */
    public function getUser_Id() {
        if (isset($this->user_id)) {
            return $this->user_id;
        } else {
            return null;
        }
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setUser_Id($user_id) {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of time
     */
    public function getTime() {
        if (isset($this->time)) {
            return $this->time;
        } else {
            return null;
        }
    }

    /**
     * Set the value of time
     *
     * @return  self
     */
    public function setTime($time) {
        $this->time = $time;

        return $this;
    }
}
