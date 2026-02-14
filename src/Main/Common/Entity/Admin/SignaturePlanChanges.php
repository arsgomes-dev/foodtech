<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class SignaturePlanChanges extends ModelClass {

    protected $table_db = "signatures_plan_changes";
    protected $logTimestamp = false;
    protected $table_db_primaryKey = "id";
    private int $id;
    private string $signature_gcid;
    private int $old_access_plan_id;
    private int $new_access_plan_id;
    private float $old_price;
    private float $new_price;
    private string $change_type;
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

    public function getOld_access_plan_id() {
        return $this->old_access_plan_id ?? null;
    }

    public function setOld_access_plan_id(int $old_access_plan_id) {
        $this->old_access_plan_id = $old_access_plan_id;
    }

    public function getNew_access_plan_id() {
        return $this->new_access_plan_id ?? null;
    }

    public function setNew_access_plan_id(int $new_access_plan_id) {
        $this->new_access_plan_id = $new_access_plan_id;
    }

    public function getOld_price() {
        return $this->old_price ?? null;
    }

    public function setOld_price(float $old_price) {
        $this->old_price = $old_price;
    }

    public function getNew_price() {
        return $this->new_price ?? null;
    }

    public function setNew_price(float $new_price) {
        $this->new_price = $new_price;
    }

    public function getChange_type() {
        return $this->change_type ?? null;
    }

    public function setChange_type(string $change_type) {
        $this->change_type = $change_type; // upgrade | downgrade | lateral
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

    public function getCreated_at() {
        return $this->created_at ?? null;
    }
}
