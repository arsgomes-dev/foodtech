<?php
namespace Microfw\Src\Main\Common\Entity\Admin;

class Address {

    public $cep;
    public $address;
    public $neighborhood;
    public $city;
    public $uf;
    public $state;
    public $region;
    public $ibge;
    public $ddd;
    public $siafi;
    public $erro;

    public function __construct($cep, $address, $neighborhood, $city, $uf, $state, $region, $ibge, $ddd, $siafi, $erro = null) {
        $this->cep = $cep;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->uf = $uf;
        $this->state = $state;
        $this->region = $region;
        $this->ibge = $ibge;
        $this->ddd = $ddd;
        $this->siafi = $siafi;
        $this->erro = $erro;
    }
}
