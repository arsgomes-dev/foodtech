<?php

namespace Microfw\Src\Main\Business\Service\Admin;

use Microfw\Src\Main\Dao\Factory\Admin\FactoryDAO;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;

/**
 * Description of KeepPerseveringService
 *
 * @author ARGomes
 */
//TODO: Classe responsável por fornecer as entidades, as funções do banco de dados. 
//Obs.: Não alterar sem ter os conhecimentos necessários

class KeepPerseveringService extends FactoryDAO {

    public function getQuery(
            $classB = null,
            bool $single = false, // true retorna 1 objeto, false retorna array
            int $limit = 0,
            int $offset = 0,
            string $order = '',
            bool $and_or = false, // AND ou OR entre condições do objeto
            array $less_equal = [],
            array $greater_equal = [],
            array $customWhere = [], // AND customizado
            array $customWhereOr = []) {

        $config = new McConfig;
        return $this::query($config->getDb(), $this, $classB, $single, $limit, $offset, $order, $and_or, $less_equal, $greater_equal, $customWhere, $customWhereOr);
    }

    public function getCountSumQuery(
            $classB = null,
            array $sumColumns = [], // ex: ['views','likes']
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = [],
            array $customWhere = [],
            array $customWhereOr = []
    ) {
        $config = new McConfig;
        return $this::countSumQuery($config->getDb(), $this, $classB, $sumColumns, $and_or, $less_equal, $greater_equal, $customWhere, $customWhereOr);
    }

    public function setSaveQuery() {
        if ($this) {
            $config = new McConfig;
            return $this->saveQuery($config->getDb());
        }
    }

    public function setDeleteQuery() {
        if ($this) {
            $config = new McConfig;
            return $this->deleteQuery($config->getDb());
        }
    }
}
