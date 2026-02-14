<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

use Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;

class SignaturePayment extends ModelClass {

    protected $table_db = "signatures_payments";
    protected $table_db_primaryKey = "id";
    protected string $gcid;
    private int $id;
    private $signature_id;
    private string $date_billing;
    private string $date_due;
    private string $date_payment;
    private string $payment_token;
    private string $card_mask;
    private int $installment;
    private int $payment_status_id;
    private $payment_status;
    private int $payment_config_id;
    private int $payment_charge_id;
    private int $payment_method_id;
    private $payment_method;
    private bool $nfse_sent;
    private bool $nfse_issued;
    private int $user_id_updated;

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

    public function getSignature_id() {
        if (isset($this->signature_id)) {
            return $this->signature_id;
        } else {
            return null;
        }
    }

    public function setSignature_id($signature_id) {
        $this->signature_id = $signature_id;
    }

    public function getDate_billing() {
        if (isset($this->date_billing)) {
            return $this->date_billing;
        } else {
            return null;
        }
    }

    public function setDate_billing(string $date_billing) {
        $this->date_billing = $date_billing;
    }

    public function getDate_due() {
        if (isset($this->date_due)) {
            return $this->date_due;
        } else {
            return null;
        }
    }

    public function setDate_due(string $date_due) {
        $this->date_due = $date_due;
    }

    public function getDate_payment() {
        if (isset($this->date_payment)) {
            return $this->date_payment;
        } else {
            return null;
        }
    }

    public function setDate_payment(string $date_payment) {
        $date_payment = date('Y-m-d', strtotime(str_replace("/", "-", $date_payment)));
        $this->date_payment = $date_payment;
    }

    public function getPayment_token() {
        if (isset($this->payment_token)) {
            return $this->payment_token;
        } else {
            return null;
        }
    }

    public function setPayment_token(string $payment_token) {
        $this->payment_token = $payment_token;
    }
    
    
    public function getCard_mask() {
        if (isset($this->card_mask)) {
            return $this->card_mask;
        } else {
            return null;
        }
    }

    public function setCard_mask(string $card_mask) {
        $this->card_mask = $card_mask;
    }


    public function getInstallment() {
        if (isset($this->installment)) {
            return $this->installment;
        } else {
            return null;
        }
    }

    public function setInstallment(int $installment) {
        $this->installment = $installment;
    }

    public function getPayment_status_id() {
        if (isset($this->payment_status_id)) {
            return $this->payment_status_id;
        } else {
            return null;
        }
    }

    public function setPayment_status_id(int $payment_status_id) {
        $this->payment_status_id = $payment_status_id;
    }

    public function getPayment_status() {
        if (isset($this->payment_status)) {
            return $this->payment_status;
        } else {
            return null;
        }
    }

    public function setPayment_status($payment_status) {
        $this->payment_status = $payment_status;
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

    public function getPayment_charge_id() {
        if (isset($this->payment_charge_id)) {
            return $this->payment_charge_id;
        } else {
            return null;
        }
    }

    public function setPayment_charge_id(int $payment_charge_id) {
        $this->payment_charge_id = $payment_charge_id;
    }

    public function getPayment_method_id() {
        if (isset($this->payment_method_id)) {
            return $this->payment_method_id;
        } else {
            return null;
        }
    }

    public function setPayment_method_id(int $payment_method_id) {
        $this->payment_method_id = $payment_method_id;
    }

    public function getPayment_method() {
        if (isset($this->payment_method)) {
            return $this->payment_method;
        } else {
            return null;
        }
    }

    public function setPayment_method($payment_method) {
        $this->payment_method = $payment_method;
    }

    public function getNfse_sent() {
        if (isset($this->nfse_sent)) {
            return $this->nfse_sent;
        } else {
            return null;
        }
    }

    public function setNfse_sent(bool $nfse_sent) {
        $this->nfse_sent = $nfse_sent;
    }

    public function getNfse_issued() {
        if (isset($this->nfse_issued)) {
            return $this->nfse_issued;
        } else {
            return null;
        }
    }

    public function setNfse_issued(bool $nfse_issued) {
        $this->nfse_issued = $nfse_issued;
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