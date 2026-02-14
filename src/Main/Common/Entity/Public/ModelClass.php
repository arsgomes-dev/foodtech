<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Business\Service\Public\KeepPerseveringService;
use Microfw\Src\Main\Common\Settings\MagicalMethods;

#[\AllowDynamicProperties]
class ModelClass extends KeepPerseveringService {

    //Deve-se importar a classe MagicalMethods pois ela define o que a função do banco de dados deve ou não ignorar.
    use MagicalMethods;

    public function __construct() {
        $this->getDefineSettings();
    }

    //essas variáveis a seguir serão ignorados pelo banco de dados
    //essa variável define se a obtenção da datetime para hora do cadastro/atualização será feita de forma automática.
    protected $logTimestamp = true;
    //define o nome da tabela dessa classe no banco de dados.
    protected $table_db = "";
    //define que colunas no banco de dados deverá ser consultadas com a utilização do LIKE no MYSQL.
    protected $table_columns_like_db = [];
    //define que colunas no banco de dados deverá ser usadas como AND após WHERE de insert/update no MYSQL.
    protected $table_columns_and_db = [];
    //define que colunas no banco de dados deverá ser como atomic no MYSQL.
    protected $table_columns_atomic_db = [];
    //define que colunas no banco de dados deverá ser consultadas com a utilização do >= no MYSQL.
    protected $table_columns_greater_equal_db = [];
    //define que colunas no banco de dados deverá ser consultadas com a utilização do <= no MYSQL.
    protected $table_columns_less_equal_db = [];
    //define que colunas no banco de dados deverá ser consultadas com a utilização do Between no MYSQL.
    protected $table_columns_between_db = [];
    //define que colunas o função SUM vai realizar a soma dos valores
    protected $table_columns_sum_db = "";
    //define a coluna que será utilizada como identificador único nessa tabela desse banco de dados.
    private $table_db_primaryKey = "";
    //caso $logTimestamp seja true é necessário acrescentar as variáveis a seguir ($criated_at, $updated_at):    
    private string $created_at; //armazenará a data de criação da linha na tabela
    private string $updated_at; //armazenará a data de atualização dessa linha na tabela. 
    //retorna o total do count
    private string $total;

    //É necessário adicionar os gets das variáveis anteriores.

    public function setLogTimestamp($logTimestamp) {
        $this->logTimestamp = $logTimestamp;
    }

    public function getLogTimestamp() {
        return $this->logTimestamp;
    }

    public function getTable_db() {
        return $this->table_db;
    }

    public function getTable_columns_like_db() {
        return $this->table_columns_like_db;
    }

    public function getTable_columns_and_db() {
        return $this->table_columns_and_db;
    }

    public function getTable_columns_atomic_db() {
        return $this->table_columns_atomic_db;
    }

    public function getTable_db_primaryKey() {
        return $this->table_db_primaryKey;
    }

    public function setTable_db_primaryKey($table_db_primaryKey) {
        $this->table_db_primaryKey = $table_db_primaryKey;
    }

    public function getTable_columns_greater_equal_db() {
        return $this->table_columns_greater_equal_db;
    }

    public function setTable_columns_greater_equal_db($table_columns_greater_equal_db) {
        $this->table_columns_greater_equal_db = $table_columns_greater_equal_db;
    }

    public function getTable_columns_less_equal_db() {
        return $this->table_columns_less_equal_db;
    }

    public function setTable_columns_less_equal_db($table_columns_less_equal_db) {
        $this->table_columns_less_equal_db = $table_columns_less_equal_db;
    }

    public function getTable_columns_between_db() {
        return $this->table_columns_between_db;
    }

    public function setTable_columns_between_db($table_columns_between_db) {
        $this->table_columns_between_db = $table_columns_between_db;
    }

    public function getTable_columns_sum_db() {
        return $this->table_columns_sum_db;
    }

    public function setTable_columns_sum_db($table_columns_sum_db) {
        $this->table_columns_sum_db = $table_columns_sum_db;
    }

    public function getCreated_at() {
        if (isset($this->created_at)) {
            return $this->created_at;
        } else {
            return null;
        }
    }

    public function getUpdated_at() {
        if (isset($this->updated_at)) {
            return $this->updated_at;
        } else {
            return null;
        }
    }

    public function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }

    public function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }

    public function getTotal() {
        if (isset($this->total)) {
            return $this->total;
        } else {
            return null;
        }
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}
