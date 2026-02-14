<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;

class ClientUsageCounter extends ModelClass {

    protected $table_db = "customer_usage_counters";
    private $table_db_primaryKey = "id";
    protected $table_columns_and_db = ['month_year'];
    protected $table_columns_atomic_db = ['tokens_used', 'scripts_used'];
    private $id;
    private $customer_id;
    private $month_year;
    private $tokens_used;
    private $scripts_used;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function setCustomer_id($customer_id) {
        $this->customer_id = $customer_id;
    }

    public function getMonth_year() {
        if (isset($this->month_year)) {
            return $this->month_year;
        } else {
            return null;
        }
    }

    public function setMonth_year($month_year) {
        $this->month_year = $month_year;
    }

    public function getTokens_used() {
        if (isset($this->tokens_used)) {
            return $this->tokens_used;
        } else {
            return null;
        }
    }

    public function setTokens_used($tokens_used) {
        $this->tokens_used = $tokens_used;
    }

    public function getScripts_used() {
        if (isset($this->scripts_used)) {
            return $this->scripts_used;
        } else {
            return null;
        }
    }

    public function setScripts_used($scripts_used) {
        $this->scripts_used = $scripts_used;
    }
}
