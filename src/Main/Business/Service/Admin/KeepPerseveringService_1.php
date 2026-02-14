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

    public function getOne(
            $parameter = null
    ) {
        if ($parameter !== null) {
            $config = new McConfig;
            return $this::one($config->getDb(), $this, $parameter);
        }
    }

    public function getAll(
            int $limit = 0,
            int $offset = 0,
            string $order = '',
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = []
    ) {
        $config = new McConfig;
        return $this::all($config->getDb(), $this, $limit, $offset, $order, $and_or, $less_equal, $greater_equal);
    }

    public function getCount(
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = []
    ) {
        $config = new McConfig;
        return $this::count($config->getDb(), $this, $and_or, $less_equal, $greater_equal);
    }

   public function getSum(
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = []
    ) {
        $config = new McConfig;
        return $this::sum($config->getDb(), $this, $and_or, $less_equal, $greater_equal);
    }

   public function getSumJoin(
            $classB,
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = []
    ) {
        $config = new McConfig;
        return $this::sumJoin($config->getDb(), $this, $classB, $and_or, $less_equal, $greater_equal);
    }

    public function getAllJoin(
            $classB,
            int $limit = 0,
            int $offset = 0,
            string $order = '',
            bool $and_or = false,
            array $less_equal = [],
            array $greater_equal = []
    ) {
        $config = new McConfig;
        return $this::allJoin($config->getDb(), $this, $classB, $limit, $offset, $order, $and_or, $less_equal, $greater_equal);
    }

    public function setSave() {
        if ($this) {
            $config = new McConfig;
            return $this->save($config->getDb());
        }
    }

    public function setDelete() {
        if ($this) {
            $config = new McConfig;
            return $this->delete($config->getDb());
        }
    }
}
