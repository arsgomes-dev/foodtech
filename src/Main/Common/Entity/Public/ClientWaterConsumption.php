<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientWaterConsumption extends ModelClass {

    protected $table_db = "customer_water_consumption";
    protected $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private string $evaluation_date;
    private $quantity_ml;

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

    public function getEvaluation_date() {
        if (isset($this->evaluation_date)) {
            return $this->evaluation_date;
        } else {
            return null;
        }
    }

    public function setEvaluation_date(string $evaluation_date) {
        $this->evaluation_date = $evaluation_date;
    }

    public function getQuantity_ml() {
        if (isset($this->quantity_ml)) {
            return $this->quantity_ml;
        } else {
            return null;
        }
    }

    public function setQuantity_ml($quantity_ml) {
        $this->quantity_ml = $quantity_ml;
    }

}

?>

