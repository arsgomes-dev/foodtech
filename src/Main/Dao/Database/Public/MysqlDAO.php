<?php

namespace Microfw\Src\Main\Dao\Database\Public;

use PDO;
use Exception;
use Microfw\Src\Main\Common\Entity\Public\Mysql;

class MysqlDAO extends \stdClass {
    /* ============================================================
     * Exemplos
      // Retorna array de objetos
      $allScripts = Scripts::daoQuery(
      new Scripts(),
      single: false,
      limit: 10,
      order: "created_at DESC",
      customWhere: [['column'=>'channel','value'=>1]],
      customWhereOr: [['column'=>'status','values'=>[1,2,3]]]
      );

      // Retorna apenas 1 objeto
      $script = Scripts::daoQuery(
      new Scripts(),
      single: true,
      customWhere: [['column'=>'id','value'=>123]]
      );

     * Exemplos
      $channels = MysqlGeneralDAO::daoQuery(
      new Channels(),
      new Customer(name: "Andre")
      );
     * 
     * date_end IS NULL
     * $whereNull = ['A.date_end'];
     * 
     * status != 1
     * $whereNot = ['A.status' => 1];
     * 
     * date_end IS NOT NULL
     * $whereNot = ['A.date_end' => null];
     * 
     * Combinação
     * $whereNull = ['A.date_end'];     * 
     * $whereNot  = ['A.status' => 0];
     * ============================================================ */

    public static function daoQuery(
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
        $table_nameA = SETTING_DB_TABLE_PREFIX . (
                $classA->getTable_db() ?: strtolower(explode(SETTING_DIR_ENTITY, get_class($classA))[1]) . "s"
                );

        $mysql = new Mysql();
        $pdo = $mysql->getPDO();

        $sql = "SELECT A.* FROM `$table_nameA` A";
        $conditions = [];
        $params = [];

        // JOIN
        if ($classB !== null) {
            $table_nameB = SETTING_DB_TABLE_PREFIX . (
                    $classB->getTable_db() ?: strtolower(explode(SETTING_DIR_ENTITY, get_class($classB))[1]) . "s"
                    );
            $sql .= " LEFT JOIN `$table_nameB` B 
                  ON A." . $classA->getTable_db_join() . " = B." . $classB->getTable_db_join();
        }

        // Builder por entidade (corrigido)
        $buildConditions = function ($obj, $alias) use (&$conditions, &$params) {
            foreach ($obj->getMethodsName() as $col) {
                $value = $obj->{"get" . ucfirst($col)}();

                // NÃO gerar IS NULL automaticamente
                if ($value === null || $value === "")
                    continue;

                $field = "$alias.$col";
                $conditions[] = "$field = :{$alias}_$col";
                $params["{$alias}_$col"] = $value;
            }
        };

        $buildConditions($classA, 'A');
        if ($classB !== null)
            $buildConditions($classB, 'B');

        // <=
        foreach ($less_equal as $k => $v) {
            $p = str_replace('.', '', $k) . "_le";
            $conditions[] = "$k <= :$p";
            $params[$p] = $v;
        }

        // >=
        foreach ($greater_equal as $k => $v) {
            $p = str_replace('.', '', $k) . "_ge";
            $conditions[] = "$k >= :$p";
            $params[$p] = $v;
        }

        // AND =
        foreach ($customWhere as $i => $rule) {
            $p = "cw_$i";
            $conditions[] = "{$rule['column']} = :$p";
            $params[$p] = $rule['value'];
        }

        // OR
        foreach ($customWhereOr as $g => $group) {
            $or = [];
            foreach ($group['values'] as $i => $v) {
                $p = "or_{$g}_{$i}";
                $or[] = "{$group['column']} = :$p";
                $params[$p] = $v;
            }
            $conditions[] = "(" . implode(" OR ", $or) . ")";
        }

        // IS NULL explícito
        foreach ($whereNull as $col)
            $conditions[] = "$col IS NULL";

        // != / IS NOT NULL
        foreach ($whereNot as $k => $v) {
            if ($v === null) {
                $conditions[] = "$k IS NOT NULL";
            } else {
                $p = str_replace('.', '', $k) . "_ne";
                $conditions[] = "$k <> :$p";
                $params[$p] = $v;
            }
        }

        // IN
        foreach ($whereIn as $k => $values) {
            $in = [];
            foreach ($values as $i => $v) {
                $p = str_replace('.', '', $k) . "_in_$i";
                $in[] = ":$p";
                $params[$p] = $v;
            }
            $conditions[] = "$k IN (" . implode(',', $in) . ")";
        }

        // NOT IN
        foreach ($whereNotIn as $k => $values) {
            $in = [];
            foreach ($values as $i => $v) {
                $p = str_replace('.', '', $k) . "_nin_$i";
                $in[] = ":$p";
                $params[$p] = $v;
            }
            $conditions[] = "$k NOT IN (" . implode(',', $in) . ")";
        }

        if ($conditions)
            $sql .= " WHERE " . implode($and_or ? " OR " : " AND ", $conditions);

        if ($groupBy)
            $sql .= " GROUP BY " . implode(', ', $groupBy);

        if ($having)
            $sql .= " HAVING " . implode(' AND ', $having);

        if ($order)
            $sql .= " ORDER BY $order";

        if ($limit > 0)
            $sql .= " LIMIT $limit";

        if ($offset > 0)
            $sql .= " OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v)
            $stmt->bindValue(":$k", $v);

        $stmt->execute();

        // =========================
        // RETORNO (PADRÃO ORIGINAL)
        // =========================

        if ($single) {
            $obj = $stmt->fetchObject(get_called_class());
            if (!$obj)
                return null;

            $entity = new $classA;
            foreach ((array) $obj as $key => $value) {
                if (is_scalar($value)) {
                    $setter = "set" . ucfirst($key);
                    $entity->$setter($value);
                }
            }
            return $entity;
        }

        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
        $result = [];

        foreach ($rows as $obj) {
            $entity = new $classA;
            foreach ((array) $obj as $key => $value) {
                if (is_scalar($value)) {
                    $setter = "set" . ucfirst($key);
                    $entity->$setter($value);
                }
            }
            $result[] = $entity;
        }

        return $result;
    }

    /* ============================================================
     * Exemplos
      $result = Scripts::daoCountSum(
      new Scripts(),
      sumColumns: ['views','likes'],
      customWhere: [['column'=>'channel','value'=>1]],
      customWhereOr: [['column'=>'status','values'=>[1,2,3]]]
      );

      echo "Total linhas: " . $result['total_count'] . "\n";
      echo "Total views: " . $result['total_views'] . "\n";
      echo "Total likes: " . $result['total_likes'] . "\n";

     * ============================================================ */

    public static function daoCountSumQuery(
            $classA,
            $classB = null,
            array $sumColumns = [], // ex: ['views','likes']
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = [],
            array $customWhere = [],
            array $customWhereOr = []
    ) {
        $table_nameA = SETTING_DB_TABLE_PREFIX . (
                ($classA->getTable_db() !== "") ? $classA->getTable_db() : strtolower(explode(SETTING_DIR_ENTITY, get_class($classA))[1]) . "s"
                );

        $mysql = new Mysql();
        $pdo = $mysql->getPDO();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Monta SELECT com COUNT(*) e SUM(col)
        $selectParts = ["COUNT(*) AS total_count"];
        foreach ($sumColumns as $col) {
            $selectParts[] = "SUM(A.$col) AS total_$col";
        }

        $sql = "SELECT " . implode(", ", $selectParts) . " FROM `$table_nameA` A";

        // JOIN opcional
        if ($classB !== null) {
            $table_nameB = SETTING_DB_TABLE_PREFIX . (
                    ($classB->getTable_db() !== "") ? $classB->getTable_db() : strtolower(explode(SETTING_DIR_ENTITY, get_class($classB))[1]) . "s"
                    );
            $sql .= " LEFT JOIN `$table_nameB` B ON A." . $classA->getTable_db_join() . " = B." . $classB->getTable_db_join();
        }

        $conditions = [];
        $params = [];

        // Função para criar filtros
        $buildConditions = function ($obj, $prefix = '') use (&$conditions, &$params) {
            $cols = $obj->getMethodsName();
            $cols_like = $obj->getTable_columns_like_db();
            $cols_less = $obj->getTable_columns_less_equal_db();
            $cols_greater = $obj->getTable_columns_greater_equal_db();
            $cols_between = $obj->getTable_columns_between_db();

            foreach ($cols as $col) {
                $method = "get" . ucfirst($col);
                $value = $obj->$method();
                if ($value === null || $value === "")
                    continue;

                $colAlias = $prefix ? $prefix . "." . $col : $col;

                if (in_array($col, $cols_between)) {
                    $startParam = str_replace('.', '', $colAlias) . '_start';
                    $endParam = str_replace('.', '', $colAlias) . '_end';
                    $conditions[] = "$colAlias BETWEEN :$startParam AND :$endParam";
                    $params[$startParam] = $value . ' 00:00:00';
                    $params[$endParam] = $value . ' 23:59:59';
                } else {
                    $operator = "=";
                    if (in_array($col, $cols_like))
                        $operator = " LIKE ";
                    elseif (in_array($col, $cols_less))
                        $operator = " <= ";
                    elseif (in_array($col, $cols_greater))
                        $operator = " >= ";
                    $conditions[] = "$colAlias $operator :$col";
                    $params[$col] = $operator === " LIKE " ? "%$value%" : $value;
                }
            }
        };

        // Campos do objeto
        $buildConditions($classA, "A");
        if ($classB !== null)
            $buildConditions($classB, "B");

        // Less/Greater
        foreach ($less_equal as $k => $v) {
            $param = str_replace('.', '', $k) . "_le";
            $conditions[] = "$k <= :$param";
            $params[$param] = $v;
        }
        foreach ($greater_equal as $k => $v) {
            $param = str_replace('.', '', $k) . "_ge";
            $conditions[] = "$k >= :$param";
            $params[$param] = $v;
        }

        // customWhere AND
        foreach ($customWhere as $i => $rule) {
            $col = $rule['column'];
            $param = str_replace('.', '', $col) . "_cw_$i";
            $conditions[] = "$col = :$param";
            $params[$param] = $rule['value'];
        }

        // customWhere OR
        foreach ($customWhereOr as $g => $group) {
            $col = $group['column'];
            $orParts = [];
            foreach ($group['values'] as $i => $v) {
                $param = str_replace('.', '', $col) . "_or_{$g}_{$i}";
                $orParts[] = "$col = :$param";
                $params[$param] = $v;
            }
            $conditions[] = "(" . implode(" OR ", $orParts) . ")";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode($and_or ? " OR " : " AND ", $conditions);
        }

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v)
            $stmt->bindValue(":$k", $v);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // ['total_count'=>10,'total_views'=>123,'total_likes'=>456]
    }

    public static function daoSaveQuery($class) {
        $mysql = new Mysql();
        $pdo = $mysql->getPDO();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $tableName = SETTING_DB_TABLE_PREFIX . (
                $class->getTable_db() !== "" ? $class->getTable_db() : strtolower(explode(SETTING_DIR_ENTITY, get_class($class))[1]) . "s"
                );

        $primaryKey = $class->getTable_db_primaryKey() ?: SETTING_DB_FIELD_PRIMARYKEY;

        // ------------------------------
        // 🔍 Monta array de colunas válidas (ignora NULL e "")
        // ------------------------------
        $columns = $class->getMethodsName();
        $data = [];
        foreach ($columns as $col) {

            if ($col === $primaryKey)
                continue;

            $method = "get" . ucfirst($col);
            $value = $class->$method();

            // ❗ IGNORA campos vazios
            if ($value === null || $value === "")
                continue;

            $data[$col] = $value;
        }

        try {
            $pdo->beginTransaction();

            $idMethod = "get" . ucfirst($primaryKey);
            $id = method_exists($class, $idMethod) ? $class->$idMethod() : null;

            // Função interna para fazer bind
            $bindValues = function ($stmt, $values) {
                foreach ($values as $k => $v) {
                    $stmt->bindValue(":$k", $v, PDO::PARAM_STR);
                }
            };

            // -------------------------------------------------
            // 🔥 UPDATE
            // -------------------------------------------------
            if ($id) {

                if (empty($data)) {
                    throw new Exception("Nenhum campo válido para UPDATE.");
                }

                $fields = [];
                foreach ($data as $col => $v)
                    $fields[] = "`$col` = :$col";

                if ($class->getLogTimestamp())
                    $fields[] = "updated_at = :updated_at";

                $sql = "UPDATE `$tableName` SET " . implode(", ", $fields) .
                        " WHERE `$primaryKey` = :$primaryKey";

                $stmt = $pdo->prepare($sql);

                $bindValues($stmt, $data);

                if ($class->getLogTimestamp())
                    $stmt->bindValue(":updated_at", $class->getDateTime(), PDO::PARAM_STR);

                $stmt->bindValue(":$primaryKey", $id);

                $stmt->execute();
                $pdo->commit();
                return 1; // atualizado
            }

            // -------------------------------------------------
            // 🔥 INSERT
            // -------------------------------------------------
            $cols = array_keys($data);
            $placeholders = array_map(fn($c) => ":$c", $cols);

            if ($class->getLogTimestamp()) {
                $cols[] = "created_at";
                $placeholders[] = ":created_at";
            }

            if (empty($cols)) {
                throw new Exception("INSERT sem campos válidos.");
            }

            $sql = "INSERT INTO `$tableName` (" . implode(", ", $cols) .
                    ") VALUES (" . implode(", ", $placeholders) . ")";

            $stmt = $pdo->prepare($sql);

            $bindValues($stmt, $data);

            if ($class->getLogTimestamp())
                $stmt->bindValue(":created_at", $class->getDateTime(), PDO::PARAM_STR);

            $stmt->execute();
            $pdo->commit();

            return 2; // inserido
        } catch (\Exception $ex) {
            $pdo->rollBack();
            throw new \Exception("Erro ao salvar no banco: " . $ex->getMessage());
        }
    }

    public static function daoDeleteQuery($class) {
        $mysql = new Mysql();
        $pdo = $mysql->getPDO();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $tableName = SETTING_DB_TABLE_PREFIX . (
                $class->getTable_db() !== "" ? $class->getTable_db() : strtolower(explode(SETTING_DIR_ENTITY, get_class($class))[1]) . "s"
                );

        $primaryKey = $class->getTable_db_primaryKey() ?: SETTING_DB_FIELD_PRIMARYKEY;
        $getter = "get" . ucfirst($primaryKey);
        $valueId = method_exists($class, $getter) ? $class->$getter() : null;

        if ($valueId === null) {
            throw new \Exception("Valor da chave primária não definido para exclusão.");
        }

        try {
            $sql = "DELETE FROM `$tableName` WHERE `$primaryKey` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":id", $valueId, PDO::PARAM_INT);

            return $stmt->execute() ? 1 : 2; // 1 = deletado, 2 = falhou
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao excluir do banco: " . $ex->getMessage());
        }
    }
}
