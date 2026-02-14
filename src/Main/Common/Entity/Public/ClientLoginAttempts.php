<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;

class ClientLoginAttempts extends ModelClass {

    protected $table_db = "clientloginattempts";
    protected $logTimestamp = false;
    private $table_db_primaryKey = "client_id";
    private int $client_id;
    private int $time;

    /**
     * Get the value of id
     */
    public function getClient_Id() {
        if (isset($this->client_id)) {
            return $this->client_id;
        } else {
            return null;
        }
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setClient_Id($client_id) {
        $this->client_id = $client_id;

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
