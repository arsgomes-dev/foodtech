<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class PaymentStatus extends ModelClass {

    protected $table_db = "payment_status";
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $payment_config_id;
    private string $title;
    private string $description;
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