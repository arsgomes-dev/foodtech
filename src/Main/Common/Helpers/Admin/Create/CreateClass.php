<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Helpers\Admin\Create;

/**
 * Description of CreateClass
 *
 * @author Windows 11
 */
class CreateClass {

    function createEntity($nameClass, $tableDb, $likeDb, $idPrimary, $fields, $gcid = false) {
        //$nameClass, $tableDb, $likeDb, $idPrimary, $gcid, $gcidGeneration, $fields
        $break = chr(13) . chr(10);
        $title = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Common/Entity/' . ucfirst($nameClass) . '.php';
        $content = '<?php' . $break;
        $content .= '' . $break;
        $content .= 'namespace Microfw\Src\Main\Common\Entity;' . $break;
        $content .= $break;
        if ($gcid === true) {
            $content .= 'use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;' . $break;
            $content .= '' . $break;
        }
        $content .= 'class ' . ucfirst($nameClass) . ' extends ModelClass {' . $break;
        $content .= '' . $break;
        $content .= '    protected $table_db = "' . strtolower($tableDb) . '";';
        $content .= '' . $break;
        if (!isset($likeDb) && $likeDb !== null) {
            $like = explode(",", $likeDb);
            $likes = '';
            for ($i = 0; $i < count($like); $i++) {
                $likes .= '"' . strtolower($like[$i]) . '"';
            }
            $content .= '    protected $table_columns_like_db = [' . $likes . '];';
            $content .= '' . $break;
        }
        $content .= '    protected $table_db_primaryKey = "' . $idPrimary . '";';
        if ($gcid === true) {
            $content .= '' . $break;
            $content .= '    protected string $gcid;';
        }
        $content .= '' . $break;
        $fieldsTemps = explode(",", $fields);
        for ($a = 0; $a < count($fieldsTemps); $a++) {
            $field = explode("->", $fieldsTemps[$a]);
            $content .= '    private ' . trim(strtolower($field[0])) . ' $' . strtolower($field[1]) . ';';
            $content .= '' . $break;
        }
        if ($gcid === true) {
            $content .= '' . $break;
            $content .= '' . $break;
            $content .= '    public function getGcid() {';
            $content .= '' . $break;
            $content .= '        if (isset($this->gcid)) {';
            $content .= '' . $break;
            $content .= '            return $this->gcid;';
            $content .= '' . $break;
            $content .= '        } else {';
            $content .= '' . $break;
            $content .= '            return null;';
            $content .= '' . $break;
            $content .= '        }';
            $content .= '' . $break;
            $content .= '    }';
            $content .= '' . $break;
            $content .= '' . $break;
            $content .= '    public function setGcid($gcid = null) {';
            $content .= '' . $break;
            $content .= '    ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();';
            $content .= '' . $break;
            $content .= '        return $this;';
            $content .= '' . $break;
            $content .= '    }';
            $content .= '' . $break;
            $content .= '' . $break;
        }
        for ($c = 0; $c < count($fieldsTemps); $c++) {
            $field = explode("->", $fieldsTemps[$c]);
            $content .= '' . $break;
            $content .= '    public function get' . ucfirst(strtolower($field[1])) . '() {';
            $content .= '' . $break;
            $content .= '        if (isset($this->' . strtolower($field[1]) . ')) {';
            $content .= '' . $break;
            $content .= '            return $this->' . strtolower($field[1]) . ';';
            $content .= '' . $break;
            $content .= '        } else {';
            $content .= '' . $break;
            $content .= '            return null;';
            $content .= '' . $break;
            $content .= '        }';
            $content .= '' . $break;
            $content .= '    }';
            $content .= '' . $break;
            $content .= '' . $break;
            $content .= '    public function set' . ucfirst(strtolower($field[1])) . '(' . strtolower($field[0]) . ' $' . strtolower($field[1]) . ') {';
            $content .= '' . $break;
            $content .= '    $this->' . strtolower($field[1]) . ' = $' . strtolower($field[1]) . ';';
            $content .= '' . $break;
            $content .= '    }';
            $content .= '' . $break;
        }
        $content .= ' }' . $break;
        $content .= '?>';

        $files = fopen($title, 'w');
        fwrite($files, $content);
        fclose($files);
    }
}
