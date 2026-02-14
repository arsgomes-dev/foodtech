<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use PDO;

class Mysql {

    private static $pdo = null;
    private $url = '', $dbname = '', $charset = '', $username = '', $passwd = '';

    public function __construct() {
        $this->url = env('DB_HOST_CLIENT');
        $this->dbname = env('DB_DATABASE_CLIENT');
        $this->charset = env('DB_CHARSET_CLIENT');
        $this->username = env('DB_USERNAME_CLIENT');
        $this->passwd = env('DB_PASSWD_CLIENT');
    }

    public function getPDO() {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                    "mysql:dbname={$this->dbname};host={$this->url};charset={$this->charset}",
                    $this->username,
                    $this->passwd
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
