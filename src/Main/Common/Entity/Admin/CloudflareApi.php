<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of CloudflareApi
 *
 * @author Ricardo Gomes
 */
class CloudflareApi extends ModelClass {

    protected $table_db = "cloudflare_api";
    private $table_db_primaryKey = "id";
    protected $logTimestamp = true;
    private int $id;
    private int $user_id_updated;
    private string $cust_email;
    private string $cust_xauth;
    private string $cust_domain;
    private string $cust_zone;

    public function getId(){
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser_id_updated($user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function setCust_email($cust_email) {
        $this->cust_email = $cust_email;
    }

    public function setCust_xauth($cust_xauth) {
        $this->cust_xauth = $cust_xauth;
    }

    public function setCust_domain($cust_domain) {
        $this->cust_domain = $cust_domain;
    }

    public function setCust_zone($cust_zone) {
        $this->cust_zone = $cust_zone;
    }
}
