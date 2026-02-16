<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientNutritionalAssessment extends ModelClass {

    protected $table_db = "customer_nutritional_assessment";
    protected $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private string $description;
    private string $type;
    private $calorie_factor;

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

    public function getType() {
        if (isset($this->type)) {
            return $this->type;
        } else {
            return null;
        }
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getCalorie_factor() {
        if (isset($this->calorie_factor)) {
            return $this->calorie_factor;
        } else {
            return null;
        }
    }

    public function setCalorie_factor($calorie_factor) {
        $this->calorie_factor = $calorie_factor;
    }

}

?>

