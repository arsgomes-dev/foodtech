<?php

namespace Microfw\Src\Main\Common\Entity\Public;

class Currency extends ModelClass {

    protected $logTimestamp = false;
    protected $table_db = "currency";
    private $table_db_primaryKey = "id";
    private int $id;
    private string $title;
    private string $currency;
    private string $locale;
    private string $placeholder;
    private string $status;
    private string $active;

    #[\Override]
    public function getLogTimestamp() {
        return $this->logTimestamp;
    }

    #[\Override]
    public function getTable_db() {
        return $this->table_db;
    }

    public function getTable_id_db() {
        return $this->table_id_db;
    }

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function getCurrency() {
        if (isset($this->currency)) {
            return $this->currency;
        } else {
            return null;
        }
    }

    public function getLocale() {
        if (isset($this->locale)) {
            return $this->locale;
        } else {
            return null;
        }
    }

    public function getPlaceholder() {
        if (isset($this->placeholder)) {
            return $this->placeholder;
        } else {
            return null;
        }
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function getActive() {
        if (isset($this->active)) {
            return $this->active;
        } else {
            return null;
        }
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setTitle(String $title): void {
        $this->title = $title;
    }

    public function setCurrency(string $currency) {
        $this->currency = $currency;
    }

    public function setLocale(string $locale) {
        $this->locale = $locale;
    }

    public function setPlaceholder(string $placeholder) {
        $this->placeholder = $placeholder;
    }

    public function setStatus(string $status) {
        $this->status = $status;
    }

    public function setActive(string $active) {
        $this->active = $active;
    }
}
