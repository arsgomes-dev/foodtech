<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class FoodVitamin extends ModelClass {

    protected $table_db = "food_vitamins";
    protected $table_db_primaryKey = "id";
    private  $id;
    private  $food_id;
    private  $retinol_mcg;
    private  $re_mcg;
    private  $rae_mcg;
    private  $thiamin_mg;
    private  $riboflavin_mg;
    private  $pyridoxine_mg;
    private  $niacin_mg;
    private  $vitamin_c_mg;
    private  $folate_mcg;
    private  $cobalamin_mcg;
    private  $calciferol_mcg;
    private  $vitamin_e_mg;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId( $id) {
        $this->id = $id;
    }

    public function getFood_id() {
        if (isset($this->food_id)) {
            return $this->food_id;
        } else {
            return null;
        }
    }

    public function setFood_id( $food_id) {
        $this->food_id = $food_id;
    }

    public function getRetinol_mcg() {
        if (isset($this->retinol_mcg)) {
            return $this->retinol_mcg;
        } else {
            return null;
        }
    }

    public function setRetinol_mcg( $retinol_mcg) {
        $this->retinol_mcg = $retinol_mcg;
    }

    public function getRe_mcg() {
        if (isset($this->re_mcg)) {
            return $this->re_mcg;
        } else {
            return null;
        }
    }

    public function setRe_mcg( $re_mcg) {
        $this->re_mcg = $re_mcg;
    }

    public function getRae_mcg() {
        if (isset($this->rae_mcg)) {
            return $this->rae_mcg;
        } else {
            return null;
        }
    }

    public function setRae_mcg( $rae_mcg) {
        $this->rae_mcg = $rae_mcg;
    }

    public function getThiamin_mg() {
        if (isset($this->thiamin_mg)) {
            return $this->thiamin_mg;
        } else {
            return null;
        }
    }

    public function setThiamin_mg( $thiamin_mg) {
        $this->thiamin_mg = $thiamin_mg;
    }

    public function getRiboflavin_mg() {
        if (isset($this->riboflavin_mg)) {
            return $this->riboflavin_mg;
        } else {
            return null;
        }
    }

    public function setRiboflavin_mg( $riboflavin_mg) {
        $this->riboflavin_mg = $riboflavin_mg;
    }

    public function getPyridoxine_mg() {
        if (isset($this->pyridoxine_mg)) {
            return $this->pyridoxine_mg;
        } else {
            return null;
        }
    }

    public function setPyridoxine_mg( $pyridoxine_mg) {
        $this->pyridoxine_mg = $pyridoxine_mg;
    }

    public function getNiacin_mg() {
        if (isset($this->niacin_mg)) {
            return $this->niacin_mg;
        } else {
            return null;
        }
    }

    public function setNiacin_mg( $niacin_mg) {
        $this->niacin_mg = $niacin_mg;
    }

    public function getVitamin_c_mg() {
        if (isset($this->vitamin_c_mg)) {
            return $this->vitamin_c_mg;
        } else {
            return null;
        }
    }

    public function setVitamin_c_mg( $vitamin_c_mg) {
        $this->vitamin_c_mg = $vitamin_c_mg;
    }

    public function getFolate_mcg() {
        if (isset($this->folate_mcg)) {
            return $this->folate_mcg;
        } else {
            return null;
        }
    }

    public function setFolate_mcg( $folate_mcg) {
        $this->folate_mcg = $folate_mcg;
    }

    public function getCobalamin_mcg() {
        if (isset($this->cobalamin_mcg)) {
            return $this->cobalamin_mcg;
        } else {
            return null;
        }
    }

    public function setCobalamin_mcg( $cobalamin_mcg) {
        $this->cobalamin_mcg = $cobalamin_mcg;
    }

    public function getCalciferol_mcg() {
        if (isset($this->calciferol_mcg)) {
            return $this->calciferol_mcg;
        } else {
            return null;
        }
    }

    public function setCalciferol_mcg( $calciferol_mcg) {
        $this->calciferol_mcg = $calciferol_mcg;
    }

    public function getVitamin_e_mg() {
        if (isset($this->vitamin_e_mg)) {
            return $this->vitamin_e_mg;
        } else {
            return null;
        }
    }

    public function setVitamin_e_mg( $vitamin_e_mg) {
        $this->vitamin_e_mg = $vitamin_e_mg;
    }
}

?>