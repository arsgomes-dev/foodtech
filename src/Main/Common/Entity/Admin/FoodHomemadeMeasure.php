<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of FoodHomemadeMeasure
 *
 * @author Ricardo Gomes
 */
class FoodHomemadeMeasure extends ModelClass {

    protected $table_db = "food_homemade_measure";
    protected $table_columns_like_db = ['measure'];
    protected $table_db_primaryKey = "id";
    private $id;
    private $food_id;
    private string $measure;
    private $grammage;
    private $user_id_created;
    private $user_id_updated;
    private $customer_id;

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

    public function getFood_id() {
        if (isset($this->food_id)) {
            return $this->food_id;
        } else {
            return null;
        }
    }

    public function setFood_id($food_id) {
        $this->food_id = $food_id;
    }

    public function setMeasure($measure) {
        $this->measure = $measure;
    }

    public function getMeasure() {
        if (isset($this->measure)) {
            return $this->measure;
        } else {
            return null;
        }
    }

    public function setGrammage($grammage) {
        $this->grammage = $grammage;
    }

    public function getGrammage() {
        if (isset($this->grammage)) {
            return $this->grammage;
        } else {
            return null;
        }
    }

    public function getUser_id_created() {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function setUser_id_created($user_id_created) {
        $this->user_id_created = $user_id_created;
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function setUser_id_updated($user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function setCustomer_id($customer_id) {
        $this->customer_id = $customer_id;
    }
}
