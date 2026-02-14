<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;

class CloudflareApi extends ModelClass {

    protected $table_db = "cloudflare_api";
    private $table_db_primaryKey = "id";
    protected $logTimestamp = true;
    private $id;
    private $user_id_updated;
    private string $cust_email;
    private string $cust_xauth;
    private string $cust_domain;
    private string $cust_zone;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function getCust_email() {
        if (isset($this->cust_email)) {
            return $this->cust_email;
        } else {
            return null;
        }
    }

    public function getCust_xauth() {
        if (isset($this->cust_xauth)) {
            return $this->cust_xauth;
        } else {
            return null;
        }
    }

    public function getCust_domain() {
        if (isset($this->cust_domain)) {
            return $this->cust_domain;
        } else {
            return null;
        }
    }

    public function getCust_zone() {
        if (isset($this->cust_zone)) {
            return $this->cust_zone;
        } else {
            return null;
        }
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setUser_id_updated(int $user_id_updated): void {
        $this->user_id_updated = $user_id_updated;
    }

    public function setCust_email(string $cust_email): void {
        $this->cust_email = $cust_email;
    }

    public function setCust_xauth(string $cust_xauth): void {
        $this->cust_xauth = $cust_xauth;
    }

    public function setCust_domain(string $cust_domain): void {
        $this->cust_domain = $cust_domain;
    }

    public function setCust_zone(string $cust_zone): void {
        $this->cust_zone = $cust_zone;
    }
}
