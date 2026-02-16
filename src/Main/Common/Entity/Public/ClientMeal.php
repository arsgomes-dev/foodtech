<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientMeal extends ModelClass {

    protected $table_db = "customer_meals";
    protected $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private int $meal_type_id;
    private int $food_id;
    private $observations;
    private string $date_time;

    public function getTable_db_join() {
        if (isset($this->table_db_join)) {
            return $this->table_db_join;
        } else {
            return null;
        }
    }

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

    public function getGcid() {
        if (isset($this->gcid)) {
            return $this->gcid;
        } else {
            return null;
        }
    }

    public function setGcid($gcid = null) {
        ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();
        return $this;
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function setCustomer_id(int $customer_id) {
        $this->customer_id = $customer_id;
    }

    public function getMeal_type_id() {
        if (isset($this->meal_type_id)) {
            return $this->meal_type_id;
        } else {
            return null;
        }
    }

    public function setMeal_type_id(int $meal_type_id) {
        $this->meal_type_id = $meal_type_id;
    }

    public function getFood_id() {
        if (isset($this->food_id)) {
            return $this->food_id;
        } else {
            return null;
        }
    }

    public function setFood_id(int $food_id) {
        $this->food_id = $food_id;
    }

    public function getObservations() {
        if (isset($this->observations)) {
            return $this->observations;
        } else {
            return null;
        }
    }

    public function setObservations($observations) {
        $this->observations = $observations;
    }

    public function getDate_time() {
        if (isset($this->date_time)) {
            return $this->date_time;
        } else {
            return null;
        }
    }

    public function setDate_time(string $date_time) {
        $this->date_time = $date_time;
    }

}

?>

