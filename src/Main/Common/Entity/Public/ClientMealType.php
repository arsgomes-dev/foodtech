<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientMealType extends ModelClass {

    protected $table_db = "customer_meals_types";
    protected $table_db_primaryKey = "id";
    private $id;
    private string $description;
    private int $orders;
    private int $user_created_by;
    private int $user_updated_by;

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

    public function getOrders() {
        if (isset($this->orders)) {
            return $this->orders;
        } else {
            return null;
        }
    }

    public function setOrders(int $orders) {
        $this->orders = $orders;
    }

    public function getUser_created_by() {
        if (isset($this->user_created_by)) {
            return $this->user_created_by;
        } else {
            return null;
        }
    }

    public function setUser_created_by(int $user_created_by) {
        $this->user_created_by = $user_created_by;
    }

    public function getUser_updated_by() {
        if (isset($this->user_updated_by)) {
            return $this->user_updated_by;
        } else {
            return null;
        }
    }

    public function setUser_updated_by(int $user_updated_by) {
        $this->user_updated_by = $user_updated_by;
    }

}

?>

