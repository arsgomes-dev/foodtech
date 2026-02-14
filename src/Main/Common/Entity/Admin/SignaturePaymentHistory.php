<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class SignaturePaymentHistory extends ModelClass {

    protected $table_db = "signatures_payments_status_history";
    protected $logTimestamp = false;
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $signature_payment_id;
    private $old_status;
    private string $new_status;
    private string $changed_by;
    private $reason;

    /* ==========================
     * ID
     * ========================== */

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

    /* ==========================
     * SIGNATURE PAYMENT ID
     * ========================== */

    public function getSignature_payment_id() {
        if (isset($this->signature_payment_id)) {
            return $this->signature_payment_id;
        } else {
            return null;
        }
    }

    public function setSignature_payment_id(int $signature_payment_id) {
        $this->signature_payment_id = $signature_payment_id;
    }

    /* ==========================
     * OLD STATUS
     * ========================== */

    public function getOld_status() {
        if (isset($this->old_status)) {
            return $this->old_status;
        } else {
            return null;
        }
    }

    public function setOld_status($old_status = null) {
        $this->old_status = $old_status;
    }

    /* ==========================
     * NEW STATUS
     * ========================== */

    public function getNew_status() {
        if (isset($this->new_status)) {
            return $this->new_status;
        } else {
            return null;
        }
    }

    public function setNew_status(string $new_status) {
        $this->new_status = $new_status;
    }

    /* ==========================
     * CHANGED BY
     * ========================== */

    public function getChanged_by() {
        if (isset($this->changed_by)) {
            return $this->changed_by;
        } else {
            return null;
        }
    }

    public function setChanged_by(string $changed_by) {
        $this->changed_by = $changed_by;
    }

    /* ==========================
     * REASON
     * ========================== */

    public function getReason() {
        if (isset($this->reason)) {
            return $this->reason;
        } else {
            return null;
        }
    }

    public function setReason($reason = null) {
        $this->reason = $reason;
    }
}
