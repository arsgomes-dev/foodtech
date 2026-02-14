<?php

namespace Microfw\Src\Main\Common\Entity\Public;

class SignatureTerms extends ModelClass {

    protected $table_db = "signatures_terms";
    protected $table_db_primaryKey = "id";
    private int $id;
    private string $version;
    private string $title;
    private string $term;
    private string $type;
    private int $user_id_created;
    private int $user_id_updated;
    private int $status;

    public function getId() {
        return $this->id ?? null;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getVersion() {
        return $this->version ?? null;
    }

    public function setVersion(string $version) {
        $this->version = $version;
    }

    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function setTitle(String $title): void {
        $this->title = $title;
    }

    public function getTerm() {
        return $this->term ?? null;
    }

    public function setTerm(string $term) {
        $this->term = $term;
    }

    public function getType() {
        return $this->type ?? null;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getUser_id_created() {
        return $this->user_id_created ?? null;
    }

    public function setUser_id_created(int $user_id_created) {
        $this->user_id_created = $user_id_created;
    }

    public function getUser_id_updated() {
        return $this->user_id_updated ?? null;
    }

    public function setUser_id_updated(int $user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function getStatus() {
        return $this->status ?? null;
    }

    public function setStatus(int $status) {
        $this->status = $status; // 1 = ativo | 0 = inativo
    }
}
