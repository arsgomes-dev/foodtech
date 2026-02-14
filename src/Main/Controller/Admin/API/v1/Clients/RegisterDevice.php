<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Controller\Admin\API\v1\Clients;

use Microfw\Src\Main\Common\Entity\Admin\Customers;

/**
 * Description of RegisterDevice
 *
 * @author Ricardo Gomes
 */
class RegisterDevice {

    function setStoreDevicePublicPrivateKey($gcid, $publicKeyBinary, $privateKeyBinary) {
        // TODO: store in DB.
        // Example: use PDO to insert/update a table device_keys (user_id, device_id, public_key_base64, created_at)
        $publicB64 = base64_encode($publicKeyBinary);
        $customer = new Customers();
        $customer->setTable_db_primaryKey("gcid");
        $customer->setGcid($gcid);
        $customer->setPublic_key($publicB64);
        $customer->setPrivate_key($privateKeyBinary);
        $customer->setSave();
    }
}
