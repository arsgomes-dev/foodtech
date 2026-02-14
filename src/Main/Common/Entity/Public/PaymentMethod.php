<?php

namespace Microfw\Src\Main\Common\Entity\Public;

class PaymentMethod extends ModelClass {

    protected $table_db = "payment_methods";
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $payment_config_id;
    private string $payment_method;
    private string $title;
    private int $status;

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

    public function getPayment_config_id() {
        if (isset($this->payment_config_id)) {
            return $this->payment_config_id;
        } else {
            return null;
        }
    }

    public function setPayment_config_id(int $payment_config_id) {
        $this->payment_config_id = $payment_config_id;
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

    public function getPayment_method() {
        if (isset($this->payment_method)) {
            return $this->payment_method;
        } else {
            return null;
        }
    }

    public function setPayment_method(string $payment_method) {
        $this->payment_method = $payment_method;
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
}

?>