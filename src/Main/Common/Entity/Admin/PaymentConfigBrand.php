<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class PaymentConfigBrand extends ModelClass {

    protected $table_db = "payment_config_brand";
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $payment_config_id;
    private string $title;
    private string $description;
    private string $image;
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

    public function getImage() {
        if (isset($this->image)) {
            return $this->image;
        } else {
            return null;
        }
    }

    public function setImage(string $image) {
        $this->image = $image;
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
