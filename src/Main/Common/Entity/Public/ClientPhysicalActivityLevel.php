<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;

class ClientPhysicalActivityLevel extends ModelClass {

    protected $table_db = "customer_physical_activity_levels";
    protected $table_db_primaryKey = "id";

    private $id;
    private string $formula;
    private string $title;
    private string $description;
    private $multiplier_factor;
    private $display_order;
    private $icon;
    private $is_active;

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

    public function getFormula()
    {
        if (isset($this->formula)) {
            return $this->formula;
        } else {
            return null;
        }
    }

    public function setFormula(string $formula)
    {
        $this->formula = $formula;
    }

    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getMultiplier_factor() {
        if (isset($this->multiplier_factor)) {
            return $this->multiplier_factor;
        } else {
            return null;
        }
    }

    public function setMultiplier_factor($multiplier_factor) {
        $this->multiplier_factor = $multiplier_factor;
    }

    public function getDisplay_order() {
        if (isset($this->display_order)) {
            return $this->display_order;
        } else {
            return null;
        }
    }

    public function setDisplay_order($display_order) {
        $this->display_order = $display_order;
    }

    public function getIcon() {
        if (isset($this->icon)) {
            return $this->icon;
        } else {
            return null;
        }
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function getIs_active() {
        if (isset($this->is_active)) {
            return $this->is_active;
        } else {
            return null;
        }
    }

    public function setIs_active($is_active) {
        $this->is_active = $is_active;
    }

}