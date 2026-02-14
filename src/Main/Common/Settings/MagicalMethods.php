<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPTrait.php to edit this template
 */

namespace Microfw\Src\Main\Common\Settings;

/**
 *
 * @author Ricardo Gomes
 */
trait MagicalMethods {

    public function getDefineSettings() {
        if (!defined('SETTING_TIMEZONE')) {
            //SETTING_TIMEZONE = Fuso horário da aplicação 
            define('SETTING_TIMEZONE', 'America/Sao_Paulo');
            //Diretório das entidades 
            define('SETTING_DIR_ENTITY', 'Microfw\\Src\\Main\\Common\\Entity\\');
            //Métodos e variáveis que irão ser ignorado das classes pela aplicação durante o tratamento das querys do banco de dados
            $methodsMagical = array('getQuery', 'getCountSumQuery', 'daoSaveQuery', 'getOne', 'getAll', 'getAllJoin', 'getCount', 'getPersCount', 'getSum', 'getSumJoin', 'getDefineSettings', 'getMethodsName', 'getDateTime', 'getDateMySQL', 'getFormatDateToMySQL', 'getFormatDateTimeToMySQL', 'getFormatDateToBrazil', 'getFormatDateTimeToBrazil', 'getGenerateUniqueGcid');
            define('SETTING_MAGICALMETHODS', $methodsMagical);
            $variablesMagical = array('getLogTimestamp', 'getTable_db', 'getTable_db_like', 'getTable_columns_greater_equal_db', 'getTable_columns_less_equal_db', 'getTable_columns_between_db', 'getTable_columns_sum_db', 'getTable_db_primaryKey', 'getTable_id_db', 'getTable_columns_db', 'getTable_columns_like_db', 'getTable_columns_and_db', 'getTable_columns_atomic_db', 'getGcid_generation', 'getTable_db_join', 'getTotal');
            define('SETTING_MAGICALVARIABLES', $variablesMagical);
            //prefixo das tabelas do banco de dados
            define('SETTING_DB_TABLE_PREFIX', 'tbsys_');
            //campo de identificação única do banco de dados
            define('SETTING_DB_FIELD_PRIMARYKEY', 'id');
        }
    }

    public function getMethodsName() {
        $cl = new $this;
        $cl->getDefineSettings();
        $methodsClass = get_class_methods($cl);
        $methods = [];
        foreach ($methodsClass as $key) {
            $method_get = trim(substr($key, 0, 3));
            if ($method_get === "get") {
                if (!in_array($key, SETTING_MAGICALMETHODS)) {
                    if (!in_array($key, SETTING_MAGICALVARIABLES)) {
                        $method = preg_replace('/^get/', '', $key);
                        array_push($methods, strtolower($method));
                    }
                }
            }
        }
        if ($methods !== null) {
            return $methods;
        }
    }

    public function getDateTime() {
        date_default_timezone_set(SETTING_TIMEZONE);
        $date2 = date('Y-m-d ');
        $time0 = date('H:i:s', time());
        return $date2 . $time0;
    }

    public function getDateMySQL() {
        date_default_timezone_set(SETTING_TIMEZONE);
        return date('Y-m-d');
    }

    function getFormatDateToMySQL($date): ?string {
        // evita trim(null)
        if ($date === null || $date === '' || (is_string($date) && trim($date) === '')) {
            return null;
        }

        $formatos = [
            'd/m/Y', 'd-m-Y', 'd.m.Y',
            'Y-m-d', 'Y/m/d', 'Y.m.d',
            'd/m/y', 'd-m-y', 'd.m.y'
        ];

        foreach ($formatos as $formato) {
            $dataObj = \DateTime::createFromFormat($formato, $date);
            $erros = \DateTime::getLastErrors();

            if ($dataObj && empty($erros['warning_count']) && empty($erros['error_count'])) {
                return $dataObj->format('Y-m-d');
            }
        }

        // fallback: somente se tiver números
        if (preg_match('/\d/', $date)) {
            $timestamp = strtotime(str_replace("/", "-", $date));
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
        }

        return null;
    }

    function getFormatDateTimeToMySQL($date): ?string {
        if ($date === null || $date === '' || (is_string($date) && trim($date) === '')) {
            return null;
        }

        $date = trim($date);

        // formatos de data + hora
        $formatosComHora = [
            'd/m/Y H:i',
            'd/m/Y H:i:s',
            'd-m-Y H:i',
            'd-m-Y H:i:s',
            'd.m.Y H:i',
            'd.m.Y H:i:s',
            'Y-m-d H:i',
            'Y-m-d H:i:s',
            'Y/m/d H:i',
            'Y/m/d H:i:s',
            'Y.m.d H:i',
            'Y.m.d H:i:s',
        ];

        // formatos somente de data
        $formatosData = [
            'd/m/Y', 'd-m-Y', 'd.m.Y',
            'Y-m-d', 'Y/m/d', 'Y.m.d',
            'd/m/y', 'd-m-y', 'd.m.y'
        ];

        // 1) Primeiro tenta validar data com hora
        foreach ($formatosComHora as $formato) {
            $dataObj = \DateTime::createFromFormat($formato, $date);
            $erros = \DateTime::getLastErrors();

            if ($dataObj && empty($erros['warning_count']) && empty($erros['error_count'])) {
                return $dataObj->format('Y-m-d H:i:s');
            }
        }

        // 2) Depois tenta validar apenas a data
        foreach ($formatosData as $formato) {
            $dataObj = \DateTime::createFromFormat($formato, $date);
            $erros = \DateTime::getLastErrors();

            if ($dataObj && empty($erros['warning_count']) && empty($erros['error_count'])) {
                return $dataObj->format('Y-m-d');
            }
        }

        // 3) Fallback: tenta interpretar qualquer coisa válida
        if (preg_match('/\d/', $date)) {
            $timestamp = strtotime(str_replace("/", "-", $date));
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        }

        return null;
    }

    function getFormatDateToBrazil($date): ?string {
        if ($date === null || $date === '' || (is_string($date) && trim($date) === '')) {
            return null;
        }

        $formatos = [
            'Y-m-d', 'Y/m/d', 'Y.m.d',
            'd/m/Y', 'd-m-Y', 'd.m.Y',
            'd/m/y', 'd-m-y', 'd.m.y'
        ];

        foreach ($formatos as $formato) {
            $dataObj = \DateTime::createFromFormat($formato, $date);
            $erros = \DateTime::getLastErrors();

            if ($dataObj && empty($erros['warning_count']) && empty($erros['error_count'])) {
                return $dataObj->format('d/m/Y');
            }
        }

        if (preg_match('/\d/', $date)) {
            $timestamp = strtotime(str_replace("/", "-", $date));
            if ($timestamp !== false) {
                return date('d/m/Y', $timestamp);
            }
        }

        return null;
    }

    function getFormatDateTimeToBrazil($date): ?string {
        if ($date === null || $date === '' || (is_string($date) && trim($date) === '')) {
            return null;
        }

        // Formatos que aceitam data e, opcionalmente, hora
        $formatos = [
            'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d',
            'Y/m/d H:i:s', 'Y/m/d H:i', 'Y/m/d',
            'Y.m.d H:i:s', 'Y.m.d H:i', 'Y.m.d',
            'd/m/Y H:i:s', 'd/m/Y H:i', 'd/m/Y',
            'd-m-Y H:i:s', 'd-m-Y H:i', 'd-m-Y',
            'd.m.Y H:i:s', 'd.m.Y H:i', 'd.m.Y',
            'd/m/y H:i:s', 'd/m/y H:i', 'd/m/y',
            'd-m-y H:i:s', 'd-m-y H:i', 'd-m-y',
            'd.m.y H:i:s', 'd.m.y H:i', 'd.m.y'
        ];

        foreach ($formatos as $formato) {
            $dataObj = \DateTime::createFromFormat($formato, $date);
            $erros = \DateTime::getLastErrors();

            if ($dataObj && empty($erros['warning_count']) && empty($erros['error_count'])) {
                // Se houver hora válida, retorna com hora
                return $dataObj->format(
                                strpos($formato, 'H') !== false ? 'd/m/Y H:i:s' : 'd/m/Y'
                        );
            }
        }

        // Fallback: strtotime()
        if (preg_match('/\d/', $date)) {
            $timestamp = strtotime(str_replace("/", "-", $date));
            if ($timestamp !== false) {
                return date(
                        strlen($date) > 10 ? 'd/m/Y H:i:s' : 'd/m/Y',
                        $timestamp
                );
            }
        }

        return null;
    }

    /**
     * Gera um GCID único para qualquer entidade.
     */
    public function getGenerateUniqueGcid($entityObject, string $column = 'gcid'): string {
        $gcid_bool = false;
        while (!$gcid_bool) {
            $entityObject->setGcid();
            $newGcid = $entityObject->getGcid();

            $check = clone $entityObject;
            $check->setTable_db_primaryKey($column);
            $exists = $check->getQuery(single: true, customWhere: [['column' => $column, 'value' => $newGcid]]);

            if (!$exists || empty($exists->getGcid())) {
                return $newGcid;
            }
        }
    }
}
