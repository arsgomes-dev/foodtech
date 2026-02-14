<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class SignatureHistory extends ModelClass {

    protected $table_db = "signatures_history";
    protected $logTimestamp = false;
    protected $table_db_primaryKey = "id";
    private int $id;
    private string $signature_gcid;
    private ?string $old_status;
    private string $new_status;
    private string $action;
    private ?string $reason;
    private int $user_id_updated;

    public function getId() {
        return $this->id ?? null;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getSignature_gcid() {
        return $this->signature_gcid ?? null;
    }

    public function setSignature_gcid(string $signature_gcid) {
        $this->signature_gcid = $signature_gcid;
    }

    public function getOld_status() {
        return $this->old_status ?? null;
    }

    public function setOld_status(?string $old_status) {
        $this->old_status = $old_status;
    }

    public function getNew_status() {
        return $this->new_status ?? null;
    }

    public function setNew_status(string $new_status) {
        $this->new_status = $new_status;
    }

    public function getAction() {
        return $this->action ?? null;
    }

    public function setAction(string $action) {
        $this->action = $action;
    }

    public function getReason() {
        return $this->reason ?? null;
    }

    public function setReason(?string $reason) {
        $this->reason = $reason;
    }

    public function getUser_id_updated() {
        return $this->user_id_updated ?? null;
    }

    public function setUser_id_updated(int $user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }
}
