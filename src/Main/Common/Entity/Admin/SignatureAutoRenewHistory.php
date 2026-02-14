<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

class SignatureAutoRenewHistory extends ModelClass {

    protected $table_db = "signatures_auto_renew_history";
    protected $logTimestamp = false;
    protected $table_db_primaryKey = "id";
    private int $id;
    private int $signature_id;
    private string $term_version;
    private string $term_hash;
    private string $term_title;
    private string $term_text;
    private string $accepted_at;
    private ?string $ip_address;
    private ?string $user_agent;
    private string $accepted_by;
    private string $source;

    public function getId() {
        return $this->id ?? null;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getSignature_id() {
        return $this->signature_id ?? null;
    }

    public function setSignature_id(int $signature_id) {
        $this->signature_id = $signature_id;
    }

    public function getTerm_version() {
        return $this->term_version ?? null;
    }

    public function setTerm_version(string $term_version) {
        $this->term_version = $term_version;
    }

    public function getTerm_hash() {
        return $this->term_hash ?? null;
    }

    public function setTerm_hash(string $term_hash) {
        $this->term_hash = $term_hash;
    }

    public function getTerm_title() {
        return $this->term_title ?? null;
    }

    public function setTerm_title(string $term_title) {
        $this->term_title = $term_title;
    }

    public function getTerm_text() {
        return $this->term_text ?? null;
    }

    public function setTerm_text(string $term_text) {
        $this->term_text = $term_text;
    }

    public function getAccepted_at() {
        return $this->accepted_at ?? null;
    }

    public function setAccepted_at(string $accepted_at) {
        $this->accepted_at = $accepted_at;
    }

    public function getIp_address() {
        return $this->ip_address ?? null;
    }

    public function setIp_address(?string $ip_address) {
        $this->ip_address = $ip_address;
    }

    public function getUser_agent() {
        return $this->user_agent ?? null;
    }

    public function setUser_agent(?string $user_agent) {
        $this->user_agent = $user_agent;
    }

    public function getAccepted_by() {
        return $this->accepted_by ?? null;
    }

    public function setAccepted_by(string $accepted_by) {
        $this->accepted_by = $accepted_by;
    }

    public function getSource() {
        return $this->source ?? null;
    }

    public function setSource(string $source) {
        $this->source = $source;
    }
}
