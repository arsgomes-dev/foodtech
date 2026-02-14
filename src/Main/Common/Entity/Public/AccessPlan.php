<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
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
    private $number_tokens;
    private $number_scripts;
    private $number_channels;
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

    public function getNumber_tokens() {
        if (isset($this->number_tokens)) {
            return $this->number_tokens;
        } else {
            return null;
        }
    }

    public function setNumber_tokens(int $number_tokens) {
        $this->number_tokens = $number_tokens;
    }

    public function getNumber_scripts() {
        if (isset($this->number_scripts)) {
            return $this->number_scripts;
        } else {
            return null;
        }
    }

    public function setNumber_scripts(int $number_scripts) {
        $this->number_scripts = $number_scripts;
    }

    public function getNumber_channels() {
        if (isset($this->number_channels)) {
            return $this->number_channels;
        } else {
            return null;
        }
    }

    public function setNumber_channels(int $number_channels) {
        $this->number_channels = $number_channels;
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