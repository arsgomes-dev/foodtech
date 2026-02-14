<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of Language
 *
 * @author Ricardo Gomes
 */
class Language extends ModelClass {

    protected $logTimestamp = false;
    protected $table_db = "language";
    protected $table_columns_like_db = ['language'];
    private $table_db_primaryKey = "id";
    private int $id;
    private int $currency_id;
    private string $language;
    private string $code;
    private string $locale;
    private string $archive;
    private string $status;
    private string $active;

    public function getLogTimestamp() {
        return $this->logTimestamp;
    }

    public function getTable_db() {
        return $this->table_db;
    }

    public function getTable_columns_like_db() {
        return $this->table_columns_like_db;
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

    public function getCurrency_id() {
        if (isset($this->currency_id)) {
            return $this->currency_id;
        } else {
            return null;
        }
    }

    public function getLanguage() {
        if (isset($this->language)) {
            return $this->language;
        } else {
            return null;
        }
    }

    public function getCode() {
        if (isset($this->code)) {
            return $this->code;
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

    public function getArchive() {
        if (isset($this->archive)) {
            return $this->archive;
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

    public function setCurrency_id(int $currency_id) {
        $this->currency_id = $currency_id;
    }

    public function setLanguage(string $language) {
        $this->language = $language;
    }

    public function setCode(string $code) {
        $this->code = $code;
    }

    public function setLocale(string $locale) {
        $this->locale = $locale;
    }

    public function setArchive(string $archive) {
        $this->archive = $archive;
    }

    public function setStatus(string $status) {
        $this->status = $status;
    }

    public function setActive(string $active) {
        $this->active = $active;
    }
}
