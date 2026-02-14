<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

use Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;

class SignaturePaymentInvoice extends ModelClass {

    protected $table_db = "signatures_payments_invoices";
    protected $table_db_primaryKey = "id";
    protected string $gcid;
    private int $id;
    private string $signature_payment_gcid;
    private string $number_invoice;
    private string $verification_code;
    private string $series_invoice;
    private string $date_issue;
    private string $total_amount;
    private string $net_amount;
    private string $consultation_url;
    private string $canceled_at;
    private string $cancel_reason;
    private string $invoice_xml;
    private string $invoice_pdf;
    private int $user_id_created;
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

    public function getSignature_payment_gcid() {
        if (isset($this->signature_payment_gcid)) {
            return $this->signature_payment_gcid;
        } else {
            return null;
        }
    }

    public function setSignature_payment_gcid(string $signature_payment_gcid) {
        $this->signature_payment_gcid = $signature_payment_gcid;
    }

    public function getNumber_invoice() {
        if (isset($this->number_invoice)) {
            return $this->number_invoice;
        } else {
            return null;
        }
    }

    public function setNumber_invoice(string $number_invoice) {
        $this->number_invoice = $number_invoice;
    }

    public function getVerification_code() {
        if (isset($this->verification_code)) {
            return $this->verification_code;
        } else {
            return null;
        }
    }

    public function setVerification_code(string $verification_code) {
        $this->verification_code = $verification_code;
    }

    public function getSeries_invoice() {
        if (isset($this->series_invoice)) {
            return $this->series_invoice;
        } else {
            return null;
        }
    }

    public function setSeries_invoice(string $series_invoice) {
        $this->series_invoice = $series_invoice;
    }

    public function getDate_issue() {
        if (isset($this->date_issue)) {
            return $this->date_issue;
        } else {
            return null;
        }
    }

    public function setDate_issue(string $date_issue) {
        $date_issue = date('Y-m-d', strtotime(str_replace("/", "-", $date_issue)));
        $this->date_issue = $date_issue;
    }

    public function getTotal_amount() {
        if (isset($this->total_amount)) {
            return $this->total_amount;
        } else {
            return null;
        }
    }

    public function setTotal_amount(string $total_amount) {
        $this->total_amount = $total_amount;
    }

    public function getNet_amount() {
        if (isset($this->net_amount)) {
            return $this->net_amount;
        } else {
            return null;
        }
    }

    public function setNet_amount(string $net_amount) {
        $this->net_amount = $net_amount;
    }

    public function getConsultation_url() {
        if (isset($this->consultation_url)) {
            return $this->consultation_url;
        } else {
            return null;
        }
    }

    public function setConsultation_url(string $consultation_url) {
        $this->consultation_url = $consultation_url;
    }

    public function getCanceled_at() {
        if (isset($this->canceled_at)) {
            return $this->canceled_at;
        } else {
            return null;
        }
    }

    public function setCanceled_at(string $canceled_at) {
        $canceled_at = date('Y-m-d', strtotime(str_replace("/", "-", $canceled_at)));
        $this->canceled_at = $canceled_at;
    }

    public function getCancel_reason() {
        if (isset($this->cancel_reason)) {
            return $this->cancel_reason;
        } else {
            return null;
        }
    }

    public function setCancel_reason(string $cancel_reason) {
        $this->cancel_reason = $cancel_reason;
    }

    public function getInvoice_xml() {
        if (isset($this->invoice_xml)) {
            return $this->invoice_xml;
        } else {
            return null;
        }
    }

    public function setInvoice_xml(string $invoice_xml) {
        $this->invoice_xml = $invoice_xml;
    }

    public function getInvoice_pdf() {
        if (isset($this->invoice_pdf)) {
            return $this->invoice_pdf;
        } else {
            return null;
        }
    }

    public function setInvoice_pdf(string $invoice_pdf) {
        $this->invoice_pdf = $invoice_pdf;
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