<?php

namespace Microfw\Src\Main\Dao\Factory\Public;

use Microfw\Src\Main\Dao\Database\Public\MysqlDAO;

class FactoryDAO extends \stdClass {

    public static function query(
            $db,
            $classA,
            $classB = null,
            bool $single = false,
            int $limit = 0,
            int $offset = 0,
            string $order = '',
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = [],
            array $customWhere = [],
            array $customWhereOr = [],
            array $whereNull = [],
            array $whereNot = [],
            array $whereIn = [],
            array $whereNotIn = [],
            array $groupBy = [],
            array $having = []
    ) {
        switch ($db) {
            case 1:
                $obj = new MysqlDAO();
                return $obj::daoQuery($classA, $classB, $single, $limit, $offset, $order, $and_or, $less_equal, $greater_equal, $customWhere, $customWhereOr, $whereNull, $whereNot, $whereIn, $whereNotIn, $groupBy, $having);
                break;
        }
    }

    public static function countSumQuery(
            $db,
            $classA,
            $classB = null,
            array $sumColumns = [], // ex: ['views','likes']
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = [],
            array $customWhere = [],
            array $customWhereOr = []
    ) {
        switch ($db) {
            case 1:
                $obj = new MysqlDAO();
                return $obj::daoCountSumQuery($classA, $classB, $sumColumns, $and_or, $less_equal, $greater_equal, $customWhere, $customWhereOr);
                break;
        }
    }

    public function saveQuery($db) {
        switch ($db) {
            case 1:
                $obj = new MysqlDAO();
                return $obj::daoSaveQuery($this);
                break;
        }
    }

    public function deleteQuery($db) {
        switch ($db) {
            case 1:
                $obj = new MysqlDAO();
                return $obj::daoDeleteQuery($this);
                break;
        }
    }
}
