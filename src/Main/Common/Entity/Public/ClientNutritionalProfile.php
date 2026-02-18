<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class ClientNutritionalProfile extends ModelClass {

    protected $table_db = "customer_nutritional_profile";
    protected $table_db_primaryKey = "id";
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private $height;
    private $current_weight;
    private int $activity_level_id;
    private string $activity_level;
    private int $meta_id;
    private $imc;
    private $tmb;
    private $necessary_calories;
    private $proteins_g;
    private $carbohydrates_g;
    private $lipids_g;
    private $water_ml;
    private $used_weight;

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

    public function getHeight() {
        if (isset($this->height)) {
            return $this->height;
        } else {
            return null;
        }
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getCurrent_weight() {
        if (isset($this->current_weight)) {
            return $this->current_weight;
        } else {
            return null;
        }
    }

    public function setCurrent_weight($current_weight) {
        $this->current_weight = $current_weight;
    }

    public function getActivity_level_id() {
        if (isset($this->activity_level_id)) {
            return $this->activity_level_id;
        } else {
            return null;
        }
    }

    public function setActivity_level_id(int $activity_level_id) {
        $this->activity_level_id = $activity_level_id;
    }

    public function getActivity_level() {
        if (isset($this->activity_level)) {
            return $this->activity_level;
        } else {
            return null;
        }
    }

    public function setActivity_level(string $activity_level) {
        $this->activity_level = $activity_level;
    }

    public function getMeta_id() {
        if (isset($this->meta_id)) {
            return $this->meta_id;
        } else {
            return null;
        }
    }

    public function setMeta_id(int $meta_id) {
        $this->meta_id = $meta_id;
    }

    public function getImc() {
        if (isset($this->imc)) {
            return $this->imc;
        } else {
            return null;
        }
    }

    public function setImc($imc) {
        $this->imc = $imc;
    }

    public function getTmb() {
        if (isset($this->tmb)) {
            return $this->tmb;
        } else {
            return null;
        }
    }

    public function setTmb($tmb) {
        $this->tmb = $tmb;
    }

    public function getNecessary_calories() {
        if (isset($this->necessary_calories)) {
            return $this->necessary_calories;
        } else {
            return null;
        }
    }

    public function setNecessary_calories($necessary_calories) {
        $this->necessary_calories = $necessary_calories;
    }

    public function getProteins_g() {
        if (isset($this->proteins_g)) {
            return $this->proteins_g;
        } else {
            return null;
        }
    }

    public function setProteins_g($proteins_g) {
        $this->proteins_g = $proteins_g;
    }

    public function getCarbohydrates_g() {
        if (isset($this->carbohydrates_g)) {
            return $this->carbohydrates_g;
        } else {
            return null;
        }
    }

    public function setCarbohydrates_g($carbohydrates_g) {
        $this->carbohydrates_g = $carbohydrates_g;
    }

    public function getLipids_g() {
        if (isset($this->lipids_g)) {
            return $this->lipids_g;
        } else {
            return null;
        }
    }

    public function setLipids_g($lipids_g) {
        $this->lipids_g = $lipids_g;
    }

    public function getWater_ml() {
        if (isset($this->water_ml)) {
            return $this->water_ml;
        } else {
            return null;
        }
    }

    public function setWater_ml($water_ml) {
        $this->water_ml = $water_ml;
    }

    public function getUsed_weight() {
        if (isset($this->used_weight)) {
            return $this->used_weight;
        } else {
            return null;
        }
    }

    public function setUsed_weight($used_weight) {
        $this->used_weight = $used_weight;
    }
}

?>
