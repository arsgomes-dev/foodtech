<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class AccessPlanPrice extends ModelClass {

    protected $table_db = "access_plans_price";
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $access_plan_id;
    private int $currency_id;
    private $price;
    private $date_start;
    private $date_end;
    private string $status;
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

    public function getAccess_plan_id() {
        if (isset($this->access_plan_id)) {
            return $this->access_plan_id;
        } else {
            return null;
        }
    }

    public function setAccess_plan_id(int $access_plan_id) {
        $this->access_plan_id = $access_plan_id;
    }

    public function getCurrency_id() {
        if (isset($this->currency_id)) {
            return $this->currency_id;
        } else {
            return null;
        }
    }

    public function setCurrency_id(int $currency_id) {
        $this->currency_id = $currency_id;
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