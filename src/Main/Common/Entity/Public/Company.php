<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;

class Company extends ModelClass {

    protected $table_db = "company";
    private $table_db_primaryKey = "id";
    private $id;
    private $user_id_updated;
    private string $name_company;
    private string $name_fantasy;
    private string $cnpj;
    private string $municipal_registration;
    private $state_registration;
    private string $email;
    private string $contact;
    private string $logo;
    private string $opening;
    private string $andress_cep;
    private string $andress_street;
    private string $andress_number;
    private $andress_complement;
    private string $andress_neighbhood;
    private string $andress_city;
    private string $andress_state;

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

    public function getName_company() {
        if (isset($this->name_company)) {
            return $this->name_company;
        } else {
            return null;
        }
    }

    public function getName_fantasy() {
        if (isset($this->name_fantasy)) {
            return $this->name_fantasy;
        } else {
            return null;
        }
    }

    public function getCnpj() {
        if (isset($this->cnpj)) {
            return $this->cnpj;
        } else {
            return null;
        }
    }

    public function getMunicipal_registration() {
        if (isset($this->municipal_registration)) {
            return $this->municipal_registration;
        } else {
            return null;
        }
    }

    public function getState_registration() {
        if (isset($this->state_registration)) {
            return $this->state_registration;
        } else {
            return null;
        }
    }

    public function getEmail() {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return null;
        }
    }

    public function getContact() {
        if (isset($this->contact)) {
            return $this->contact;
        } else {
            return null;
        }
    }

    public function getLogo() {
        if (isset($this->logo)) {
            return $this->logo;
        } else {
            return null;
        }
    }

    public function getOpening() {
        if (isset($this->opening)) {
            return $this->opening;
        } else {
            return null;
        }
    }

    public function getAndress_cep() {
        if (isset($this->andress_cep)) {
            return $this->andress_cep;
        } else {
            return null;
        }
    }

    public function getAndress_street() {
        if (isset($this->andress_street)) {
            return $this->andress_street;
        } else {
            return null;
        }
    }

    public function getAndress_number() {
        if (isset($this->andress_number)) {
            return $this->andress_number;
        } else {
            return null;
        }
    }

    public function getAndress_complement() {
        if (isset($this->andress_complement)) {
            return $this->andress_complement;
        } else {
            return null;
        }
    }

    public function getAndress_neighbhood() {
        if (isset($this->andress_neighbhood)) {
            return $this->andress_neighbhood;
        } else {
            return null;
        }
    }

    public function getAndress_city() {
        if (isset($this->andress_city)) {
            return $this->andress_city;
        } else {
            return null;
        }
    }

    public function getAndress_state() {
        if (isset($this->andress_state)) {
            return $this->andress_state;
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

    public function setName_company(string $name_company) {
        $this->name_company = $name_company;
    }

    public function setName_fantasy(string $name_fantasy) {
        $this->name_fantasy = $name_fantasy;
    }

    public function setCnpj(string $cnpj) {
        $tempCnpj = str_replace(array('.', '-', '/'), "", $cnpj);
        $this->cnpj = $tempCnpj;
    }

    public function setMunicipal_registration(string $municipal_registration) {
        $this->municipal_registration = $municipal_registration;
    }

    public function setState_registration($state_registration) {
        $this->state_registration = $state_registration;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setContact(string $contact) {
        $this->contact = $contact;
    }

    public function setLogo(string $logo) {
        $this->logo = $logo;
    }

    public function setOpening(string $opening) {
        $date = date('Y-m-d', strtotime(str_replace("/", "-", $opening)));
        $this->opening = $date;
    }

    public function setAndress_cep(string $andress_cep) {
        $tempCep = str_replace(array('.', '-', '/'), "", $andress_cep);
        $this->andress_cep = $tempCep;
    }

    public function setAndress_street(string $andress_street) {
        $this->andress_street = $andress_street;
    }

    public function setAndress_number(string $andress_number) {
        $this->andress_number = $andress_number;
    }

    public function setAndress_complement($andress_complement) {
        $this->andress_complement = $andress_complement;
    }

    public function setAndress_neighbhood(string $andress_neighbhood) {
        $this->andress_neighbhood = $andress_neighbhood;
    }

    public function setAndress_city(string $andress_city) {
        $this->andress_city = $andress_city;
    }

    public function setAndress_state(string $andress_state) {
        $this->andress_state = $andress_state;
    }
}
