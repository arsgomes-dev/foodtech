<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

use Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;
use DateTime;

class AccessPlan extends ModelClass {

    protected $table_db = "access_plans";
    protected $table_columns_like_db = ["title"];
    protected $table_db_primaryKey = "id";
    private int $id;
    private bool $gcid_generation = false;
    private string $gcid;
    private string $title;
    private string $description;
    private string $observation;
    private $max_foods;
    private $max_meals_daily;
    private int $reports_enabled;
    private int $export_enabled;
    private string $ribbon_tag;
    private int $recommended;
    private $price;
    private $tax;
    private $date_start;
    private $date_end;
    private int $validation;
    private $status;
    private int $user_id_created;
    private int $user_id_updated;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getGcid_generation() {
        return $this->gcid_generation;
    }

    public function setGcid_generation($gcid_generation) {
        $this->gcid_generation = $gcid_generation;

        return $this;
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

    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function setTitle(string $title) {
        $this->title = $title;
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

    public function getObservation() {
        if (isset($this->observation)) {
            return $this->observation;
        } else {
            return null;
        }
    }

    public function setObservation(string $observation) {
        $this->observation = $observation;
    }

    public function getMax_foods() {
        if (isset($this->max_foods)) {
            return $this->max_foods;
        } else {
            return null;
        }
    }

    public function setMax_foods(int $max_foods) {
        $this->max_foods = $max_foods;
    }

    public function getMax_meals_daily() {
        if (isset($this->max_meals_daily)) {
            return $this->max_meals_daily;
        } else {
            return null;
        }
    }

    public function setMax_meals_daily(int $max_meals_daily) {
        $this->max_meals_daily = $max_meals_daily;
    }

    public function getReports_enabled() {
        if (isset($this->reports_enabled)) {
            return $this->reports_enabled;
        } else {
            return null;
        }
    }

    public function setReports_enabled(int $reports_enabled) {
        $this->reports_enabled = $reports_enabled;
    }

    public function getExport_enabled() {
        if (isset($this->export_enabled)) {
            return $this->export_enabled;
        } else {
            return null;
        }
    }

    public function setExport_enabled(int $export_enabled) {
        $this->export_enabled = $export_enabled;
    }

    public function getRibbon_tag() {
        if (isset($this->ribbon_tag)) {
            return $this->ribbon_tag;
        } else {
            return null;
        }
    }

    public function setRibbon_tag(string $ribbon_tag) {
        $this->ribbon_tag = $ribbon_tag;
    }

    public function getRecommended() {
        if (isset($this->recommended)) {
            return $this->recommended;
        } else {
            return null;
        }
    }

    public function setRecommended(int $recommended) {
        $this->recommended = $recommended;
    }

    public function getPrice() {
        if (isset($this->price)) {
            return $this->price;
        } else {
            return null;
        }
    }

    public function setPrice(string $price) {
        $this->price = $price;
    }

    public function getTax() {
        if (isset($this->tax)) {
            return $this->tax;
        } else {
            return null;
        }
    }

    public function setTax(string $tax) {
        $this->tax = $tax;
    }

    public function getDate_start() {
        if (isset($this->date_start)) {
            return $this->date_start;
        } else {
            return null;
        }
    }

    public function setDate_start($date_start) {
        $date = date('Y-m-d', strtotime(str_replace("/", "-", $date_start)));
        $this->date_start = $date;
    }

    public function getDate_end() {
        if (isset($this->date_end)) {
            return $this->date_end;
        } else {
            return null;
        }
    }

    public function setDate_end($date_end) {
        $date = date('Y-m-d', strtotime(str_replace("/", "-", $date_end)));
        $this->date_end = $date;
    }

    public function getValidation() {
        if (isset($this->validation)) {
            return $this->validation;
        } else {
            return null;
        }
    }

    public function setValidation(int $validation) {
        $this->validation = $validation;
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setStatus(int $status) {
        $this->status = $status;
    }

    public function getUser_id_created() {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function setUser_id_created(int $user_id_created) {
        $this->user_id_created = $user_id_created;
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function setUser_id_updated(int $user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }
}

?>