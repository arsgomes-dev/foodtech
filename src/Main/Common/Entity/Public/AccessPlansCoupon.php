<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class AccessPlansCoupon extends ModelClass {

    protected $table_db = "access_plans_coupons";
    protected $table_columns_like_db = ['coupon'];
    protected $table_db_primaryKey = "id";
    private int $id;
    private bool $gcid_generation = false;
    private string $gcid;
    private $coupon;
    private $discount;
    private int $amount_use;
    private int $quantity_used;
    private $date_start;
    private $date_end;
    private int $status;
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

    public function getCoupon() {
        if (isset($this->coupon)) {
            return $this->coupon;
        } else {
            return null;
        }
    }

    public function setCoupon(string $coupon) {
        $this->coupon = $coupon;
    }

    public function getDiscount() {
        if (isset($this->discount)) {
            return $this->discount;
        } else {
            return null;
        }
    }

    public function setDiscount(string $discount) {
        $this->discount = str_replace(",", ".", $discount);
    }

    public function getAmount_use() {
        if (isset($this->amount_use)) {
            return $this->amount_use;
        } else {
            return null;
        }
    }

    public function setAmount_use(int $amount_use) {
        $this->amount_use = $amount_use;
    }

    public function getQuantity_used() {
        if (isset($this->quantity_used)) {
            return $this->quantity_used;
        } else {
            return null;
        }
    }

    public function setQuantity_used(int $quantity_used) {
        $this->quantity_used = $quantity_used;
    }

    public function getDate_start() {
        if (isset($this->date_start)) {
            return $this->date_start;
        } else {
            return null;
        }
    }

    public function setDate_start(string $date_start) {
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

    public function setDate_end(string $date_end) {
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