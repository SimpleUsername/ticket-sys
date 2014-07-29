<?php

namespace com\lisa\model;

class Model {

    private static $_instance = null;

    private function __construct() {
        $this->dbh = new \PDO(DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance->dbh;
    }
} 