<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientPhysicalAssessment extends ModelClass {

    protected $table_db = "customer_physical_assessment";
    protected $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private string $measurement_date;
    private $weight;
    private $belly_circumference;
    private $thigh_circumference;
    private $calf_circumference;
    private $arm_circumference;
    private $forearm_circumference;
    private $observations;

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

    public function getMeasurement_date() {
        if (isset($this->measurement_date)) {
            return $this->measurement_date;
        } else {
            return null;
        }
    }

    public function setMeasurement_date(string $measurement_date) {
        $this->measurement_date = $measurement_date;
    }

    public function getWeight() {
        if (isset($this->weight)) {
            return $this->weight;
        } else {
            return null;
        }
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getBelly_circumference() {
        if (isset($this->belly_circumference)) {
            return $this->belly_circumference;
        } else {
            return null;
        }
    }

    public function setBelly_circumference($belly_circumference) {
        $this->belly_circumference = $belly_circumference;
    }

    public function getThigh_circumference() {
        if (isset($this->thigh_circumference)) {
            return $this->thigh_circumference;
        } else {
            return null;
        }
    }

    public function setThigh_circumference($thigh_circumference) {
        $this->thigh_circumference = $thigh_circumference;
    }

    public function getCalf_circumference() {
        if (isset($this->calf_circumference)) {
            return $this->calf_circumference;
        } else {
            return null;
        }
    }

    public function setCalf_circumference($calf_circumference) {
        $this->calf_circumference = $calf_circumference;
    }

    public function getArm_circumference() {
        if (isset($this->arm_circumference)) {
            return $this->arm_circumference;
        } else {
            return null;
        }
    }

    public function setArm_circumference($arm_circumference) {
        $this->arm_circumference = $arm_circumference;
    }

    public function getForearm_circumference() {
        if (isset($this->forearm_circumference)) {
            return $this->forearm_circumference;
        } else {
            return null;
        }
    }

    public function setForearm_circumference($forearm_circumference) {
        $this->forearm_circumference = $forearm_circumference;
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

}

?>

