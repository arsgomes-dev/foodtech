<?php

namespace Microfw\Src\Main\Controller\Admin\Privileges\Search;

//Função para selecionar os tipos de privilégios que a classe PRIVILÉGIO possui.
use Microfw\Src\Main\Common\Entity\Admin\PrivilegeType;
use Microfw\Src\Main\Common\Entity\Admin\PrivilegeTypePrivilege;

/**
 * Description of PrivilegesSearch
 *
 * @author Ricardo Gomes
 */
class Privileges {

    public function search($priv) {
        $privilege_type_privilege_temp = new PrivilegeTypePrivilege();
        $privilege_type_privilege_temp->setPrivilege_id($priv);
        $privilege_type_privilege = $privilege_type_privilege_temp->getQuery();
        $privilege_types = [];
        if (count($privilege_type_privilege) > 0) {
            for ($i = 0; $i < count($privilege_type_privilege); $i++) {
                if ($privilege_type_privilege[$i]) {
                    $ptp = new PrivilegeTypePrivilege;
                    $ptp = $privilege_type_privilege[$i];
                    $privilege_type_temp = new PrivilegeType();
                    $privilege_type_temp->setId($ptp->getPrivilege_type_id());
                    $privilege_type = $privilege_type_temp->getQuery();
                    if (count($privilege_type) > 0) {
                        $privilege_type_count = count($privilege_type);
                        for ($a = 0; $a < $privilege_type_count; $a++) {
                            if ($privilege_type[$a]) {
                                $privilege = new PrivilegeType;
                                $privilege = $privilege_type[$a];
                                array_push($privilege_types, $privilege->getDescription_type());
                            }
                        }
                    }
                }
            }
        }
        return $privilege_types;
    }

    public function searchPrivilege($priv) {
        $privilege_type_privilege_temp = new PrivilegeTypePrivilege();
        $privilege_type_privilege_temp->setPrivilege_id($priv);
        $privilege_type_privilege = $privilege_type_privilege_temp->getQuery();
        $privilege_types = [];
        if (count($privilege_type_privilege) > 0) {
            for ($i = 0; $i < count($privilege_type_privilege); $i++) {
                if ($privilege_type_privilege[$i]) {
                    $ptp = new PrivilegeTypePrivilege;
                    $ptp = $privilege_type_privilege[$i];
                    array_push($privilege_types, $ptp->getPrivilege_type_id());
                }
            }
        }
        return $privilege_types;
    }
}
