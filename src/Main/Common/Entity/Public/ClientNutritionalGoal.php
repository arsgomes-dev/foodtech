<?php

namespace Microfw\Src\Main\Common\Entity\Public;


use Microfw\Src\Main\Common\Entity\Public\ModelClass;
class ClientNutritionalGoal extends ModelClass {

    protected $table_db = "customer_nutritional_goals";
    protected $table_db_primaryKey = "id";

    private $id;
    private string $title;
    private string $description;
    private $caloric_adjustment;
    private $protein_percentage;
    private $carbohydrate_percentage;
    private $fat_percentage;
    private $display_order;
    private $icon;
    private $color;
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

    public function getCaloric_adjustment() {
        if (isset($this->caloric_adjustment)) {
            return $this->caloric_adjustment;
        } else {
            return null;
        }
    }

    public function setCaloric_adjustment($caloric_adjustment) {
        $this->caloric_adjustment = $caloric_adjustment;
    }

    public function getProtein_percentage() {
        if (isset($this->protein_percentage)) {
            return $this->protein_percentage;
        } else {
            return null;
        }
    }

    public function setProtein_percentage($protein_percentage) {
        $this->protein_percentage = $protein_percentage;
    }

    public function getCarbohydrate_percentage() {
        if (isset($this->carbohydrate_percentage)) {
            return $this->carbohydrate_percentage;
        } else {
            return null;
        }
    }

    public function setCarbohydrate_percentage($carbohydrate_percentage) {
        $this->carbohydrate_percentage = $carbohydrate_percentage;
    }

    public function getFat_percentage() {
        if (isset($this->fat_percentage)) {
            return $this->fat_percentage;
        } else {
            return null;
        }
    }

    public function setFat_percentage($fat_percentage) {
        $this->fat_percentage = $fat_percentage;
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

    public function getColor() {
        if (isset($this->color)) {
            return $this->color;
        } else {
            return null;
        }
    }

    public function setColor($color) {
        $this->color = $color;
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