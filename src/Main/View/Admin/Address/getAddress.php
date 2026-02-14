<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;
use Microfw\Src\Main\Common\Entity\Admin\Address;
use Microfw\Src\Main\Common\Helpers\Admin\GetCep\GetAddress;

ProtectedPage::protectedPage();

if (!empty($_POST['cep'])) {
    $cep = $_POST['cep'];
    $addressTemp = new GetAddress;
    $data = $addressTemp->getAddressCached($cep);
    $json = "";
    if ($data) {
        //  print_r($data);
        $address = new Address($data["cep"], $data["logradouro"], $data["bairro"], $data["localidade"],
                $data["uf"], $data["estado"], $data["regiao"], $data["ibge"], $data["ddd"], $data["siafi"]);

        // Convertendo o objeto em JSON
        $json = json_encode($address);
        echo $json;
    } else {
        echo $json;
    }
}